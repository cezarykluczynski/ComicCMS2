<?php

use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase;
use Zend\Math\Rand;

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
		$this->assertResponseStatusCode(1);

		$this->dispatch('create-admin test@example.com');
		$this->assertResponseStatusCode(1);
	}

	/**
	 * Admin can be created with two arguments.
	 */
	public function testCreateAdminActionCreatesAnAccount() {
		$email = 'admin+'.time().'@example.com';

		$this->dispatch('create-admin '.$email.' password');
		$this->assertModuleName('Admin');
		$this->assertControllerName('Admin\Controller\Auth');
		$this->assertControllerClass('AUthController');
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
}