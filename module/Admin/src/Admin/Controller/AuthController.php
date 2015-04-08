<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use User\Entity\User;
use Zend\Crypt\Password\Bcrypt;

class AuthController extends AdminController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function signinAction()
    {
        return new ViewModel();
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
