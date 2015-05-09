<?php

namespace ApplicationTest\Controller;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;
use Application\Controller\ApplicationController;
use ComicCmsTestHelper\Bootstrap;

class ApplicationControllerTest extends AbstractHttpControllerTestCase
{
    public function testGetEntityManagerReturnsInstanceOfDoctrineEntityManager() {
        $serviceManager = Bootstrap::getServiceManager();
        $controller = new ApplicationController();
        $controller->setServiceLocator($serviceManager);
        $applicationControllerEntityManager = $controller->getEntityManager();

        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $applicationControllerEntityManager);
    }
}