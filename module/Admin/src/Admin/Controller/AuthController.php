<?php
/**
 * Admin auth controller. Only admin account are currently supported,
 * and this is where all the authentication take place.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Admin\Controller;

use Zend\Mvc\InjectApplicationEventInterface;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use User\Entity\User;
use Zend\Session\Container;
use Zend\Console\Response;
use Application\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Zend\Crypt\Password\Bcrypt;

class AuthController extends AdminController implements InjectApplicationEventInterface
{
    /**
     * @return \Zend\View\Model\ViewModel|\Zend\View\Model\JsonModel
     */
    public function signinAction()
    {
        $request = $this->getRequest();
        $isConsoleRequest = $request instanceof ConsoleRequest;
        $isXmlHttpRequest = !$isConsoleRequest && $request->isXmlHttpRequest();

        if (!$isConsoleRequest && !$request->isPost()) {
            return new ViewModel();
        }

        $response = $this->getResponse();

        $email    = $isConsoleRequest ? $this->params()->fromRoute('email')    : $request->getPost('email');
        $password = $isConsoleRequest ? $this->params()->fromRoute('password') : $request->getPost('password');

        /** @var \Admin\Service\AuthService */
        $authService = $this->serviceLocator->get('Admin\Service\Auth');

        /** Set credentials, validate them, and return validation result. */
        $valid = $authService
            ->setCredentials($email, $password)
            ->validate()
            ->isValid();

        if ($valid)
        {
            $authService->authenticate();

            if ($isConsoleRequest)
            {
                $response = new Response();
                return $response->setErrorLevel(0);
            }

            $adminIndexUrl = $this->url()->fromRoute('admin-index');

            switch (true) {
                case $isXmlHttpRequest:
                    $response->setStatusCode(201);
                    return new JsonModel([
                        'url' => $adminIndexUrl,
                        'success' => true,
                    ]);
                default:
                    $response->setStatusCode(302);
                    return $this->redirect()->toUrl($adminIndexUrl);
            }
        }
        else
        {
            switch (true) {
                case $isXmlHttpRequest:
                    $response->setStatusCode(401);
                    return new JsonModel([
                        'success' => false,
                    ]);
                case $isConsoleRequest:
                    $response = new Response();
                    return $response->setErrorLevel(1);
                default:
                    $response->setStatusCode(401);
                    return new ViewModel([
                        'email' => $email,
                        'password' => $password,
                    ]);
            }
        }
    }

    /**
     * Sign out user.
     */
    public function signoutAction()
    {
        $authService = $this->serviceLocator->get('Admin\Service\Auth');
        $authService->removeUserFromSession();

        return $this->redirect()->toRoute('admin-signin');
    }

    /**
     * Console action. Returns session hash for user. Used by frontend tests.
     */
    public function getAdminSessionIdAction()
    {
        $request = $this->getRequest();

        $email = $request->getParam('email');
        $password = $request->getParam('password');

        $response = $this->forward()->dispatch('Admin\Controller\Auth', [
            'action' => 'signin',
            'email' => $email,
            'password' => $password,
        ]);

        if ($response->getErrorLevel() == 0)
        {
            $sessionStorage = $this->serviceLocator->get('Zend\Session\SessionManager');
            return $sessionStorage->getId();
        }

        return $response;
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

            $response = new Response();
            return $response->setErrorLevel(1);
        }
    }
}
