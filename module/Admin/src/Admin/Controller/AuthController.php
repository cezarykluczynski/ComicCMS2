<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use User\Entity\User;
use Zend\Session\Container;
use Zend\Crypt\Password\Bcrypt;
use Zend\Console\Response;

class AuthController extends AdminController implements \Zend\Mvc\InjectApplicationEventInterface
{
    /**
     * @return \Zend\View\Model\ViewModel|\Zend\View\Model\JsonModel
     */
    public function signinAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return new ViewModel();
        }

        $response = $this->getResponse();

        $email = $request->getPost('email');
        $password = $request->getPost('password');

        $user = $this
            ->getEntityManager()
            ->getRepository('User\Entity\User')
            ->findOneBy(array(
                'email' => $email,
            ));

        if (!$user) {
            $response->setStatusCode(401);
            if ($request->isXmlHttpRequest()) {
                return new JsonModel(array(
                        'success' => false,
                ));
            } else {
                return new ViewModel(array(
                        'email' => $email,
                        'password' => $password,
                ));
            }
        }

        $bcrypt = new Bcrypt();
        $validUser = $bcrypt->verify($password, $user->password);

        if ($validUser) {
            $userContainer = new Container('user');
            $userContainer->id = $user->id;
        }

        $url = $this->url()->fromRoute('admin-index');

        $response->setStatusCode($validUser ? 201 : 401);

        if ($request->isXmlHttpRequest()) {
            if ($validUser) {
                return new JsonModel(array(
                        'url' => $url,
                        'success' => true,
                ));
            } else {
                return new JsonModel(array(
                    'success' => false,
                ));
            }
        } elseif ($validUser) {
            $this->redirect()
                ->toUrl($url)
                ->setStatusCode(201);
        }

        return new ViewModel(array(
            'email' => $email,
            'password' => $password,
        ));
    }

    public function signoutAction()
    {
        $userContainer = new Container('user');
        $userContainer->id = null;
        $userContainer->authenticatedUser = null;

        return $this->redirect()->toRoute('admin-signin');
    }

    public function createAdminAction() {
        $request = $this->getRequest();

        $email = $request->getParam('email');
        $password = $request->getParam('password');

        $bcrypt = new Bcrypt();
        $passwordHashed = $bcrypt->create($password);

        $entityManager = $this->getEntityManager();

        $entityManager->getConnection()->beginTransaction();

        try {
            $user = new User;

            $user->email = $email;
            $user->password = $passwordHashed;

            /** Admin should have all roles. */
            $user->roles = $this
                ->getEntityManager()
                ->getRepository('User\Entity\Role')
                ->findAll();

            $entityManager->persist($user);
            $entityManager->flush();
            $entityManager->getConnection()->commit();

            return "\nAdmin account for $email created.\n\n";
        } catch (\Exception $e) {
            $entityManager->getConnection()->rollback();
            $entityManager->close();

            print_r($e->getMessage());

            $response = new Response();
            $response->setErrorLevel(1);

            return $response;
        }
    }
}
