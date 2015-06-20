<?php
/**
 * Entity repository factory that inject ZF service locator into repositories.
 * Most of the code here is taken from {@link \Doctrine\ORM\Repository\DefaultRepositoryFactory}.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author Cezary Kluczyński <cezary.kluczynski@gmail.com>
 */

namespace Application\Factory;

use Doctrine\ORM\Repository\RepositoryFactory;
use Doctrine\ORM\EntityManagerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EntityRepositoryFactory implements
    RepositoryFactory,
    ServiceLocatorAwareInterface
{
    /**
     * The list of EntityRepository instances.
     *
     * @var \Doctrine\Common\Persistence\ObjectRepository[]
     */
    private $repositoryList = array();

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
     * {@inheritdoc}
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $repositoryHash = $entityManager->getClassMetadata($entityName)->getName() . spl_object_hash($entityManager);

        if (isset($this->repositoryList[$repositoryHash])) {
            return $this->repositoryList[$repositoryHash];
        }

        $this->repositoryList[$repositoryHash] = $this->createRepository($entityManager, $entityName);

        /**
         * Inject service locator of \Zend\ServiceManager\ServiceLocatorAwareInterface
         * if implemented by repository class, stick to the old behaviour otherwise.
         */
        if ($this->repositoryList[$repositoryHash] instanceof ServiceLocatorAwareInterface)
        {
            $this->repositoryList[$repositoryHash]->setServiceLocator($this->getServiceLocator());
        }

        return $this->repositoryList[$repositoryHash];
    }

    /**
     * Create a new repository instance for an entity class.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager The EntityManager instance.
     * @param string                               $entityName    The name of the entity.
     *
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    private function createRepository(EntityManagerInterface $entityManager, $entityName)
    {
        /* @var $metadata \Doctrine\ORM\Mapping\ClassMetadata */
        $metadata            = $entityManager->getClassMetadata($entityName);
        $repositoryClassName = $metadata->customRepositoryClassName
            ?: $entityManager->getConfiguration()->getDefaultRepositoryClassName();

        return new $repositoryClassName($entityManager, $metadata);
    }
}
