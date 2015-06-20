<?php
/**
 * Tests for Comic REST controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace AssetTest\Validator;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;

/**
 * @coversDefaultClass \Asset\Validator\UploadValidator
 */
class AssetValidatorTest extends AbstractHttpControllerTestCase
{
    public $validType = 'image/png';
    public $invalidType = 'application/pdf';

    protected $sl;
    protected $config;
    protected $validatorConfig;
    protected $validator;

    public function setUpDependencies()
    {
        $this->sl = $this->getApplicationServiceLocator();
        $this->validator = $this->sl->get('Asset\UploadValidator');
        $this->config = $this->sl->get('Config');
        $this->validatorConfig = $this->config['validators']['Asset\Validator\UploadValidator'];
    }

    /**
     * Returns first message from validator.
     */
    protected function getValidatorMessage()
    {
        $messages = $this->validator->getMessages();
        return reset($messages);
    }

    public function testValidFilePassValidation()
    {
        $this->setUpDependencies();
        $valid = $this->validator->isValid([
            'size' => $this->validatorConfig['maximumSize'],
            'type' => $this->validType,
        ]);

        $this->assertTrue($valid);
    }

    /**
     * @covers ::isValid
     * @covers ::loadConfigVariables
     * @covers ::getServiceLocator
     * @covers ::setServiceLocator
     */
    public function testTooBigFileDoesNotPassValidation()
    {
        $this->setUpDependencies();
        $valid = $this->validator->isValid([
            'size' => $this->validatorConfig['maximumSize'] + 1,
        ]);

        $message = $this->getValidatorMessage();

        $this->assertFalse($valid);
        $this->assertEquals('File can\'t be larger than ' . $this->validatorConfig['maximumSize'] . ' bytes, but ' .
            ($this->validatorConfig['maximumSize'] + 1).' bytes uploaded.', $message);
    }

    /**
     * @covers ::isValid
     * @covers ::loadConfigVariables
     * @covers ::getServiceLocator
     * @covers ::setServiceLocator
     */
    public function testEmptyFileDoesNotPassValidation()
    {
        $this->setUpDependencies();
        $valid = $this->validator->isValid([
            'size' => 0,
        ]);

        $message = $this->getValidatorMessage();

        $this->assertFalse($valid);
        $this->assertEquals("File is empty.", $message);
    }

    /**
     * @covers ::isValid
     * @covers ::loadConfigVariables
     * @covers ::getServiceLocator
     * @covers ::setServiceLocator
     */
    public function testFileOfInvalidTypeDoesNotPassValidation()
    {
        $this->setUpDependencies();
        $valid = $this->validator->isValid([
            'size' => 100,
            'type' => $this->invalidType,
        ]);

        $message = $this->getValidatorMessage();

        $this->assertFalse($valid);
        $this->assertEquals('File must be one of the following types: '.$this->validatorConfig['sAllowedTypes'] .
            ', but "application/pdf" uploaded.', $message);
    }
}