<?php
/**
 * Local CDN. Resides all files in ./public/assets/ by default.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Asset\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Math\Rand;

class UploadCdn implements ServiceLocatorAwareInterface
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

    public function createFileFromUpload($file)
    {
        $paths = $this->getUniquePath($file);
        $moveResult = $this->moveUploadedFile($file, $paths);

        return array_merge($moveResult, $paths);
    }

    /**
     * Given file array, generates unique path on which file can be saved.
     * 
     * @param  array $file File array from $_FILES.
     * @return array       Array containing paths: relative (to be saved in entity), canonical (with path separators
     *                     for a current filesystem and absolute.
     */
    protected function getUniquePath($file)
    {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $name = Rand::getString(64, '1234567890abcdef');
        $realRelativePath = substr($name, 0, 2) . DIRECTORY_SEPARATOR . substr($name, 2) . '.' . $ext;
        $canonicalRelativePath = substr($name, 0, 2) . '/' . substr($name, 2) . '.' . $ext;
        $absolutePath = $this->realRelativePathToAbsolutePath($realRelativePath);

        /** If file already exsits, return furrent fuction one more time. */
        return is_file($absolutePath) ? $this->getUniquePath($file) : [
            'canonicalRelativePath' => $canonicalRelativePath,
            'realRelativePath' => $realRelativePath,
            'absolutePath' => $absolutePath,
        ];
    }

    public function realRelativePathToAbsolutePath($name)
    {
        $config = $this->getServiceLocator()->get('Config');
        $relativePathParts = explode(DIRECTORY_SEPARATOR, $name);
        return implode(DIRECTORY_SEPARATOR, array_merge([getcwd(), 'public', 'assets'], $relativePathParts));
    }

    protected function moveUploadedFile($file, $paths)
    {
        if (is_file($file['tmp_name']))
        {
            $dir = pathinfo($paths['absolutePath'], PATHINFO_DIRNAME);
            if (!is_dir($dir))
            {
                mkdir($dir, 0755, true);
            }
        }

        /** 
         * Using rename(), and not move_uploaded_file() makes it testable.
         * It should be validate before using {@link \Zend\Validator\File\UploadFile} 
         * that the file being moved is uploaded file.
         */
        if (@rename($file['tmp_name'], $paths['absolutePath']))
        {
            return [
                'success' => true,
            ];
        }
        else
        {
            return [
                'success' => false,
                'message' => 'Uploaded file cannot be moved to new location.',
            ];
        }
    }
}