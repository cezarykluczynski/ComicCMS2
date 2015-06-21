<?php
/**
 * Upload validator.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Asset\Validator;

use Zend\Validator\AbstractValidator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UploadValidator extends AbstractValidator implements ServiceLocatorAwareInterface
{
    const MSG_FILE_TOO_LARGE = 'fileTooLarge';
    const MSG_EMPTY_FILE = 'emptyFile';
    const MSG_INVALID_TYPE = 'invalidType';

    public $maximumSize;
    public $allowedTypes;

    protected $messageVariables = array();

    protected $messageTemplates = array(
        self::MSG_FILE_TOO_LARGE => "File can't be larger than %maximumSize% bytes, but %value% bytes uploaded.",
        self::MSG_EMPTY_FILE => "File is empty.",
        self::MSG_INVALID_TYPE => "File must be one of the following types: %allowedTypes%, but \"%value%\" uploaded.",
    );

    /**
     * {@inheritdoc}
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Whether the uploaded file is valid.
     *
     * @param array $file File array, passed from $_FILES global variable.
     * @return boolean    True, if file array is valid, false otherwise.
     */
    public function isValid($file)
    {
        $this->loadConfigVariables();

        /** Make sure the size is correct. */
        if ($file['size'] > $this->maximumSize)
        {
            $this->setValue($file['size']);
            $this->error(self::MSG_FILE_TOO_LARGE);
            return false;
        }

        /** Make sure file have a size. */
        if ($file['size'] === 0)
        {
            $this->error(self::MSG_EMPTY_FILE);
            return false;
        }

        /** Make sure it's an image that can be uploaded. */
        if (!in_array($file['type'], $this->asAllowedTypes))
        {
            $this->setValue($file['type']);
            $this->error(self::MSG_INVALID_TYPE);
            return false;
        }

        return true;
    }

    /**
     * Populates validator variables using global config.
     */
    public function loadConfigVariables()
    {
        /** @var array Global config. */
        $config = $this->getServiceLocator()->get('Config');
        /** @var array Config for class validator. */
        $validatorConfig = $config['validators'][__CLASS__];

        /** Populate. */
        foreach($validatorConfig as $variableName => $variableValue)
        {
            $this->$variableName = $variableValue;
        }

        $this->abstractOptions['messageVariables'] = $this->messageVariables;
    }
}
