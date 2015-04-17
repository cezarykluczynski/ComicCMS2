<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use User\Entity\User;
use Zend\Session\Container;
use Zend\Crypt\Password\Bcrypt;

class AuthController extends AdminController implements \Zend\Mvc\InjectApplicationEventInterface
{
    /**
     * @return \Zend\View\Model\ViewModel|Zend\View\Model\JsonModel
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

    public function createAdminAction() {
        $request = $this->getRequest();

        /** Disallow webaccess. */
        if (!$request instanceof \Zend\Console\Request) {
            throw new \RuntimeException('Admin account can be only created using CLI.');
        }

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
            $user->admin = 1;

            $entityManager->persist($user);
            $entityManager->flush();
            $entityManager->getConnection()->commit();

            return "\nAdmin account for $email created.\n\n";

        } catch (Exception $e) {
            $entityManager->getConnection()->rollback();
            $entityManager->close();

            throw $e;
        }
    }
}
