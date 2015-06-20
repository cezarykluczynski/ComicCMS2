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

        $validator = $sl->get('Asset\UploadValidator');

        /** Return error if validator fails. */
        if (!$validator->isValid($file))
        {
            $messages =  $validator->getMessages();

            return $view->setVariables([
                'success' => false,
                'message' => reset($messages),
            ]);
        }

        /** @var \Asset\Entity\ImageRepository */
        $imageRepository = $this->getEntityManager()->getRepository('Asset\Entity\Image');
        /** @var \Asset\Entity\Image */
        $image = $imageRepository->createEntityFromUpload($file);

        if ($image instanceof Image)
        {
            return $view->setVariables([
                'imageId' => $image->id,
                'success' => true,
            ]);
        }

        return $view->setVariables([
            'success' => false,
        ]);
    }
}