<?php
/**
 * Tests for upload REST controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicTest\Controller;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;
use Zend\Stdlib\Parameters;

/**
 * @coversDefaultClass \Asset\Controller\UploadRestController
 * @uses \Application\Controller\AbstractActionController
 * @uses \Application\Service\Authentication
 * @uses \Application\Service\Database
 * @uses \User\Provider\Identity\UserIdentityProvider
 * @uses \User\Provider\Identity\UserIdentityProviderMock
 * @uses \Asset\Entity\ImageRepository
 * @uses \Asset\Service\UploadCdn
 */
class UploadRestControllerTest extends AbstractHttpControllerTestCase
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
     * Test if the native {@link \Zend\Validator\File\UploadFile} is at work.
     *
     * @covers ::create
     * @covers ::validatorError
     */
    public function testInvalidFileSubmitGeneratesAnError()
    {
        $upload = new Parameters([
            'file' => [
                'name' => 'image.png',
                'tmp_name' => 'tmp_name',
                'error' => UPLOAD_ERR_NO_TMP_DIR,
            ]
        ]);

        $this->getRequest()->setFiles($upload);
        $this->dispatch('/rest/upload', 'POST');

        $response = $this->getJSONResponseAsArray();

        $this->assertFalse($response['success']);
        $this->assertEquals('No temporary directory was found for file', $response['message']);
    }

    /**
     * Only test one error from {@link \Asset\Validator\UploadValidator}.
     * Others are tested in {@link \AssetTest\Validator\UploadValidatorTest}.
     *
     * @covers ::create
     * @covers ::validatorError
     * @uses \Asset\Validator\UploadValidator
     */
    public function testFileInvalidAccordingToUploadValidatorGeneratesAnError()
    {

        $this->mockValidatorIsValid('Zend\Validator\File\UploadFile');

        $upload = new Parameters([
            'file' => [
                'name' => 'image.png',
                'tmp_name' => 'tmp_name',
                'type' => 'image/png',
                'size' => 0,
            ]
        ]);

        $this->getRequest()->setFiles($upload);
        $this->dispatch('/rest/upload', 'POST');

        $response = $this->getJSONResponseAsArray();

        $this->assertFalse($response['success']);
        $this->assertEquals('File is empty.', $response['message']);
    }

    /**
     * Skip validators, and pass non-existing file to {@link \Asset\Entity\ImageRepository::createEntityFromUpload}.
     *
     * @covers ::create
     * @covers ::validatorError
     */
    public function testMovingUploadedFileResultsInError()
    {

        $this->mockValidatorIsValid('Zend\Validator\File\UploadFile');
        $this->mockValidatorIsValid('Asset\Validator\UploadValidator', 'Asset\UploadValidator');

        $upload = new Parameters([
            'file' => [
                'name' => 'image.png',
                'tmp_name' => 'tmp_name',
                'type' => 'image/png',
                'size' => 0,
            ]
        ]);

        $this->getRequest()->setFiles($upload);
        $this->dispatch('/rest/upload', 'POST');

        $response = $this->getJSONResponseAsArray();

        $this->assertFalse($response['success']);
        $this->assertEquals('Uploaded file cannot be moved to new location.', $response['message']);
    }

    /**
     * Skip validators, and pass valid file to {@link \Asset\Entity\ImageRepository::createEntityFromUpload}.
     * @todo Move file to tmp rather than
     *
     * @covers ::create
     * @covers ::validatorError
     * @uses \Asset\Entity\Image
     */
    public function testValidFileCanBeUploaded()
    {

        $this->mockValidatorIsValid('Zend\Validator\File\UploadFile');
        $this->mockValidatorIsValid('Asset\Validator\UploadValidator', 'Asset\UploadValidator');

        /** File will be moved, so fixture has to be copied. */
        $fixture = implode(DIRECTORY_SEPARATOR, [getcwd(), 'tests', 'support', 'fixtures', 'red.png']);
        $tmpName = implode(DIRECTORY_SEPARATOR, [getcwd(), 'tests', 'support', 'fixtures', 'red_'.microtime().'.png']);
        copy($fixture, $tmpName);

        $upload = new Parameters([
            'file' => [
                'name' => 'red.png',
                'tmp_name' => $tmpName,
                'type' => 'image/png',
                'size' => filesize($tmpName),
            ],
        ]);

        $this->getRequest()->setFiles($upload);
        $this->dispatch('/rest/upload', 'POST');

        $response = $this->getJSONResponseAsArray();

        $this->assertTrue($response['success']);
        $this->assertInternalType("int", $response['imageId']);

        /** Teardown. */
        $em = $this->getEntityManager();
        $image = $em->find('Asset\Entity\Image', $response['imageId']);
        $em->remove($image);
        $em->flush();
    }

    /**
     * Mocks validator to return true from isValid method.
     */
    protected function mockValidatorIsValid($className, $alias = null)
    {
        $alias = $alias ?: $className;

        $sl = $this->getApplicationServiceLocator();

        $mock = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));

        $sl->setAllowOverride(true);
        $sl->setService($alias, $mock);
    }
}
