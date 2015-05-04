<?php

namespace ApplicationTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Application\Controller\ApplicationController;
use ApplicationTest\Bootstrap;

class ApplicationControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(include 'config/application.config.php');
        parent::setUp();
    }

    public function testGetEntityManagerReturnsInstanceOfDoctrineEntityManager() {
        $serviceManager = Bootstrap::getServiceManager();
        $controller = new ApplicationController();
        $controller->setServiceLocator($serviceManager);
        $applicationControllerEntityManager = $controller->getEntityManager();

        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $applicationControllerEntityManager);
    }
}