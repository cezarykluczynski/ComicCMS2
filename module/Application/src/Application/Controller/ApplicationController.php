<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use User\Entity\User;

class ApplicationController extends AbstractActionController
{
    /**
     * @var DoctrineORMEntityManager
     */
    protected $entifyManager;

    /**
     * @var Object
     */
    public $adapter;

    public function getEntityManager()
    {
        if (null === $this->entifyManager) {
            $this->entifyManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entifyManager;
    }

    /**
     * Returns DB adapter.
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getDbAdapter()
    {
        if (!$this->adapter) {
            $this->adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }
}