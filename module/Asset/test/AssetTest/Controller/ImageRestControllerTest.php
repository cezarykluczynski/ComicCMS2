<?php
/**
 * Tests for image REST controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicTest\Controller;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;
use Zend\Stdlib\Parameters;

/**
 * @coversDefaultClass \Asset\Controller\ImageRestController
 * @uses \Application\Controller\AbstractActionController
 * @uses \Application\Service\Authentication
 * @uses \Application\Service\Database
 * @uses \Application\Service\Dispatcher
 * @uses \Settings\Service\Settings
 * @uses \User\Provider\Identity\UserIdentityProvider
 * @uses \User\Provider\Identity\UserIdentityProviderMock
 */
class ImageRestControllerTest extends AbstractHttpControllerTestCase
{
    /**
     * Testing an admin controller, admin access is required.
     */
    public function setUp()
    {
        parent::setUp();

        $this->grantAllRolesToUser();
    }

    /**
     * Clean admin access.
     */
    public function tearDown()
    {
        $this->revokeGrantedRoles();
    }

    /**
     * Test if existing image can be deleted.
     *
     * @covers ::delete
     */
    public function testExistingImageCanBeDeleted()
    {
        /** Setup. File will be moved, so fixture has to be copied. */
        $fixture = implode(DIRECTORY_SEPARATOR, [getcwd(), 'tests', 'support', 'fixtures', 'red.png']);
        $tmpName = implode(DIRECTORY_SEPARATOR, [getcwd(), 'tests', 'support', 'fixtures', 'red_'.microtime().'.png']);
        copy($fixture, $tmpName);
        $em = $this->getEntityManager();
        $imageRepository = $em->getRepository('Asset\Entity\Image');

        $entityCreationResult = $imageRepository->createEntityFromUpload([
            'name' => 'red.png',
            'tmp_name' => $tmpName,
            'size' => filesize($tmpName),
            'type' => 'image/png',
            'error' => 0,
        ]);

        $imageId = $entityCreationResult['image']->id;

        /** Assert that file was copied by CDN class. */
        $this->assertFileEquals($fixture, $entityCreationResult['absolutePath']);

        $this->getRequest()->setMethod('DELETE');
        $this->dispatch('/rest/image/'.$imageId);

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(200);

        /** Try to find entities that should have been deleted. */
        $image = $this->em->find('Asset\Entity\Image', $imageId);

        /** Assert that the image entity was deleted along with file. */
        $this->assertNull($image);
        $this->assertFileNotExists($entityCreationResult['absolutePath']);
    }

    /**
     * Test if non-existing image can't be deleted.
     *
     * @covers ::delete
     */
    public function testNonExistingComicCantBeDeleted()
    {
        $this->getRequest()->setMethod('DELETE');
        $this->dispatch('/rest/image/'.$this->getHighestInteger());

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        /** Assert status code. */
        $this->assertResponseStatusCode(404);
        /** Assert erroe message. */
        $this->assertEquals('Image cannot be deleted, because it does not exists.', $response['message']);
    }
}