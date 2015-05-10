<?php

use ComicCmsTestHelper\Controller\AbstractConsoleControllerTestCase;
use Zend\Math\Rand;

class AuthControllerTestConsole extends AbstractConsoleControllerTestCase
{
    /**
     * Admin can be created from command line.
     */
    public function testCreateAdminActionGeneratesAnErrorWithoutEnoughParams() {
        $this->dispatch('create-admin');
        $this->assertResponseStatusCode(1, 'No params: no clean exit.');

        $this->dispatch('create-admin test@example.com');
        $this->assertResponseStatusCode(1, 'One param: no clean exit.');
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

        $this->getEntityManager();

        $admin = $this->em->getRepository('User\Entity\User')
            ->findOneBy(array(
                'email' => $email,
            ));

        $this->assertNotNull($admin, 'Something found.');
        $this->assertInstanceOf('\User\Entity\User', $admin, 'Admin found.');
        $this->assertEquals($email, $admin->email, "Admin created within this test found.");

        /** Teardown. */
        $this->em->remove($admin);
        $this->em->flush();
    }

    public function testCreateAdminActionCreatesAccountOnce() {
        /** Setup. */
        $email = 'admin+'.time().'@example.com';

        $this->dispatch('create-admin '.$email.' password');
        $this->assertResponseStatusCode(0, 'First user created.');

        /** Silence second try output. */
        ob_start();
        $this->dispatch('create-admin '.$email.' password');
        ob_end_clean();

        $this->assertResponseStatusCode(1, 'Second user not created.');
        $this->getEntityManager();

        /** Reopen entity manager after a DBAL exception. */
        if (!$this->em->isOpen()) {
            $this->em = $this->em->create(
                $this->em->getConnection(),
                $this->em->getConfiguration()
            );
        }

        $admin = $this->em->getRepository('User\Entity\User')
            ->findOneBy(array(
                'email' => $email,
            ));

        /** Teardown. */
        $this->em->remove($admin);
        $this->em->flush();
    }
}