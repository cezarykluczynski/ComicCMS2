<?php
/**
 * Image entity repository.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Asset\Entity;

use Doctrine\ORM\EntityRepository;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImageRepository extends EntityRepository implements ServiceLocatorAwareInterface
{
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
     * Save file and create entity.
     *
     * @param  array                   $file File from $_FILES global variable.
     * @return null\Asset\Entity\Image
     */
    public function createEntityFromUpload($file)
    {
        $cdn = $this->getServiceLocator()->get('Asset\UploadCdn');
        $paths = $cdn->createFileFromUpload($file);

        if (!$paths['success'])
        {
            return [
                'success' => false,
                'message' => $paths['message'],
            ];
        }

        $image = $this->createEntityFromPaths($paths);

        return array_merge([
            'image' => $image,
            'success' => true,
        ], $paths);
    }

    protected function createEntityFromPaths($paths)
    {
        $image = new Image;
        $image->canonicalRelativePath = $paths['canonicalRelativePath'];
        list($image->width, $image->height) = getimagesize($paths['absolutePath']);
        $this->_em->persist($image);
        $this->_em->flush();

        return $image;
    }
}