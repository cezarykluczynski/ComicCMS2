<?php
/**
 * Upload REST controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Asset\Controller;

use Application\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Validator\AbstractValidator;

class UploadRestController extends AbstractRestfulController
{
    /**
     * Create asset image.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function create($data)
    {
        /** @var \Zend\View\Model\JsonModel */
        $view = new JsonModel;
        /** @var \Zend\Http\Request */
        $request = $this->getRequest();
        /** @var array File array. */
        $file = $request->getFiles('file');

        /** @var \Zend\DI\ServiceLocator */
        $sl = $this->getServiceLocator();

        /** Make sure upload is valid, otherwise return error. */
        $fileUploadValidator = $sl->get('Zend\Validator\File\UploadFile');
        if (!$fileUploadValidator->isValid($file))
        {
            return $this->validatorError($view, $fileUploadValidator);
        }

        /** Make sure file is non-empty and of correct type, otherwise return error. */
        $assetUploadValidator = $sl->get('Asset\UploadValidator');
        if (!$assetUploadValidator->isValid($file))
        {
            return $this->validatorError($view, $assetUploadValidator);
        }

        /** @var \Asset\Entity\ImageRepository */
        $imageRepository = $this->getEntityManager()->getRepository('Asset\Entity\Image');
        /** @var \Asset\Entity\Image */
        $response = $imageRepository->createEntityFromUpload($file);

        if ($response['success'])
        {
            return $view->setVariables([
                'imageId' => $response['image']->id,
                'success' => true,
            ]);
        }

        return $view->setVariables([
            'success' => false,
            'message' => $response['message'],
        ]);
    }

    /**
     * Returns JSON response with validator error.
     *
     * @param \Zend\View\Model\JsonModel        $view      View instance.
     * @param \Zend\Validator\AbstractValidator $validator Validator instance.
     */
    protected function validatorError(JsonModel $view, AbstractValidator $validator)
    {
        $messages =  $validator->getMessages();

        return $view->setVariables([
            'success' => false,
            'message' => reset($messages),
        ]);
    }
}