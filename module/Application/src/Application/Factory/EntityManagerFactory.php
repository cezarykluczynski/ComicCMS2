<?php
/**
 * Entity manager factory that inject custom entity repository factory,
 * that is, {@link \Application\Factory\EntityRepositoryFactory}, into entity manager connection.
 * Most of the code here is taken from {@link DoctrineORMModule\Service\EntityManagerFactory}.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Application\Factory;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Service\AbstractFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class EntityManagerFactory extends AbstractFactory
{
    /**
     * {@inheritDoc}
     * @return EntityManager
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        /* @var $options \DoctrineORMModule\Options\EntityManager */
        $options    = $this->getOptions($sl, 'entitymanager');
        $connection = $sl->get($options->getConnection());
        $config     = $sl->get($options->getConfiguration());

       /** Instantiate custom repository factory, so service locator can be injected into factories. */
       $entityRepositoryFactory = new EntityRepositoryFactory;
       $entityRepositoryFactory->setServiceLocator($sl);

       /** Attach repository factory to entity manager. */
       $connection
           ->getConfiguration()
           ->setRepositoryFactory($entityRepositoryFactory);

        $sl->get($options->getEntityResolver());

        return EntityManager::create($connection, $config);
    }

    /**
     * {@inheritDoc}
     */
    public function getOptionsClass()
    {
        return 'DoctrineORMModule\Options\EntityManager';
    }
}
