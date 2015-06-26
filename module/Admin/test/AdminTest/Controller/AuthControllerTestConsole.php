<?php
/**
 * Tests for console authentication actions.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

use ComicCmsTestHelper\Controller\AbstractConsoleControllerTestCase;

/**
 * @coversDefaultClass \Admin\Controller\AuthController
 * @uses \Application\Controller\AbstractActionController
 * @uses \Application\Service\Authentication
 * @uses \Application\Service\Database
 * @uses \User\Provider\Identity\UserIdentityProvider
 * @uses \User\Provider\Identity\UserIdentityProviderMock
 */
class AuthControllerTestConsole extends AbstractConsoleControllerTestCase
{
    /**
     * Admin cannot be created from command line with no arguments.
     *
     * @coversNothing
     */
    public function testCreateAdminActionGeneratesAnErrorWithoutNoArguments() {
        $this->dispatch('create-admin');
        $this->assertResponseStatusCode(1);
    }

    /**
     * Admin cannot be created from command line with one argument.
     *
     * @coversNothing
     */
    public function testCreateAdminActionGeneratesAnErrorWithOneArgument() {
        $this->dispatch('create-admin test@example.com');
        $this->assertResponseStatusCode(1);
    }

    /**
     * Admin can be created with two arguments.
     *
     * Doesn't really uses {@link \Admin\Controller\AuthController::signinAction}, it's just a XDebug bugfix.
     *
     * @covers ::createAdminAction
     * @uses \Admin\Controller\AuthController::signinAction
     */
    public function testCreateAdminActionCreatesAnAccount() {
        /** Setup. */
        $email = 'admin+'.time().'@example.com';

        $this->dispatch('create-admin '.$email.' password');
        $this->assertModuleName('Admin');
        $this->assertControllerName('Admin\Controller\Auth');
        $this->assertControllerClass('AuthController');
        $this->assertMatchedRouteName('create-admin');
        $this->assertResponseStatusCode(0);
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

    /**
     * Test if only one account can be created for a given e-mail address.
     *
     * @covers ::createAdminAction
     */
    public function testCreateAdminActionCreatesAccountOnce() {
        /** Setup. */
        $email = 'admin+'.time().'@example.com';

        $this->dispatch('create-admin '.$email.' password');
        $this->assertResponseStatusCode(0);

        /** Silence output on seconds try, otherwise a SQL error will be printed. */
        ob_start();
        $this->dispatch('create-admin '.$email.' password');
        ob_end_clean();

        $this->assertResponseStatusCode(1);
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

    /**
     * Test if user session can be obtained.
     *
     * @covers ::getAdminSessionIdAction
     * @covers ::signinAction
     */
    public function testAdminSessionIdCanBeObtainedFromValidCredentials()
    {
        $this->dispatch('get-admin-session-id admin@example.com password');

        $this->assertResponseStatusCode(0);
        $this->assertRegExp('/[a-z0-9]{26,128}/', $this->getResponse()->getContent());
    }

    /**
     * Test if no user session is obtained from invalid credentials.
     *
     * @covers ::getAdminSessionIdAction
     * @covers ::signinAction
     */
    public function testAdminSessionIdCantBeObtainedFromInvalidCredentials()
    {
        $this->dispatch('get-admin-session-id admin@example.com invalid-password');

        $this->assertResponseStatusCode(1);
        $this->assertEquals('', $this->getResponse()->getContent());
    }
}
