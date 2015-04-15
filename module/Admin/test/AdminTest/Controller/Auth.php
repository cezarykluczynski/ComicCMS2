<?php

namespace AdminTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Stdlib\Parameters;

class AuthControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(include 'config/application.config.php');
        parent::setUp();
    }

    /**
     * Sign in action can be accessed.
     */
    public function testSigninActionCanBeAccessed()
    {
        $this->dispatch('/admin/signin');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Admin');
        $this->assertControllerName('Admin\Controller\Auth');
        $this->assertControllerClass('AUthController');
        $this->assertMatchedRouteName('admin-signin');
    }

    /**
     * Invalid credentials generate an error.
     */
    public function testSigninActionRespondToInvalidCredentials()
    {
    	$p = new Parameters();
    	$p->set('email','nope@example.com');
    	$p->set('password','justTooEasy');
    	$this->getRequest()->setMethod('POST');
    	$this->getRequest()->setPost($p);
    	$this->dispatch('/admin/signin');
    	$this->assertResponseStatusCode(401);
    }
}