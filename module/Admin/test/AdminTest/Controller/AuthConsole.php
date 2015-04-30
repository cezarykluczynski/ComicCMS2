<?php

use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;
use Zend\Math\Rand;
use Admin\Controller\AuthController;

class AuthControllerConsoleTest extends AbstractConsoleControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(include 'config/application.config.php');
        parent::setUp();
    }

    /**
     * Admin can be created from command line.
     */
    public function testCreateAdminActionGeneratesAnErrorWithoutEnoughParams() {
        $this->dispatch('create-admin');
        $this->assertResponseStatusCode(1, 'No params: no clean exit.');

        $this->dispatch('create-admin test@example.com');
        $this->assertResponseStatusCode(1, 'Ona param: no clean exit.');
    }

    /**
     * Admin can be created with two arguments.
     */
    public function testCreateAdminActionCreatesAnAccount() {
        /** Setup. */
        $email = 'admin+'.time().'@example.com';

        $this->dispatch('create-admin '.$email.' password');
        $this->assertModuleName('Admin');
        $this->assertControllerName('Admin\Controller\Auth');
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('create-admin');
        $this->assertResponseStatusCode(0, "Exited with 0.");
        $this->assertConsoleOutputContains("Admin account for $email created.");

        $entityManager = $this->getApplication()
            ->getServiceManager()
            ->get('doctrine.entitymanager.orm_default');

        $admin = $entityManager->getRepository('User\Entity\User')
            ->findOneBy(array(
                'email' => $email,
            ));

        $this->assertNotNull($admin, 'Something found.');
        $this->assertInstanceOf('\User\Entity\User', $admin, 'Admin found.');
        $this->assertEquals($email, $admin->email, "Admin created within this test found.");

        /** Teardown. */
        $entityManager->remove($admin);
        $entityManager->flush();
    }

    public function testCreateAdminActionCreatesAccountOnce() {
        /** Setup. */
        $email = 'admin+'.time().'@example.com';

        $this->dispatch('create-admin '.$email.' password');
        $this->assertResponseStatusCode(0, 'First user created.');
        $this->dispatch('create-admin '.$email.' password');
        $this->assertResponseStatusCode(1, 'Second user not created.');

        $entityManager = $this->getApplication()
            ->getServiceManager()
            ->get('doctrine.entitymanager.orm_default');
        
        /** Reopen entity manager after a DBAL exception. */
        if (!$entityManager->isOpen()) {
            $entityManager = $entityManager->create(
                $entityManager->getConnection(),
                $entityManager->getConfiguration()
            );
        }

        $admin = $entityManager->getRepository('User\Entity\User')
            ->findOneBy(array(
                'email' => $email,
            ));

        /** Teardown. */
        $entityManager->remove($admin);
        $entityManager->flush();
    }
}