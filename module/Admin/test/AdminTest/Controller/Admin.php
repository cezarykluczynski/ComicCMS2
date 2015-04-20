<?php

namespace AdminTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AdminControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(include 'config/application.config.php');
        parent::setUp();
    }

    /**
     * Index action can be accessed.
     */
    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/admin');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Admin');
        $this->assertControllerName('Admin\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('admin-index');
    }
}