<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use User\Entity\User;
use Zend\Session\Container;
use Zend\Mvc\MvcEvent;

class ApplicationController extends AbstractActionController
{
    public $user_id = null;
    public $user = null;

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entifyManager;

    public function onDispatch(MvcEvent $e)
    {
        $this->user_id = (new Container('user'))->id;

        if (!is_null($this->user_id)) {
            $this->user = $this
            ->getEntityManager()
            ->getRepository('User\Entity\User')
            ->findById($this->user_id);
        }

        return parent::onDispatch($e);
    }

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