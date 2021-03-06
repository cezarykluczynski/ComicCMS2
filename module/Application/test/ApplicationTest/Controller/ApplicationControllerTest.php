<?php
/**
 * Tests for a controller class extended by all other classes in the project.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ApplicationTest\Controller;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;
use Application\Controller\AbstractActionController;
use ComicCmsTestHelper\Bootstrap;

class AbstractActionControllerTest extends AbstractHttpControllerTestCase
{
    public function testGetEntityManagerReturnsInstanceOfDoctrineEntityManager() {
        $serviceManager = Bootstrap::getServiceManager();
        $controller = new AbstractActionController();
        $controller->setServiceLocator($serviceManager);
        $AbstractActionControllerEntityManager = $controller->getEntityManager();

        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $AbstractActionControllerEntityManager);
    }
}