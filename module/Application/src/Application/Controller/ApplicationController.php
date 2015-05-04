<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use User\Entity\User;
use Zend\Session\Container;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventManagerInterface;

class ApplicationController extends AbstractActionController
{
    /**
     * @var \User\Entity\User|null
     */
    protected $authenticatedUser;

    public function onDispatch(MvcEvent $e)
    {
        $userContainer = new Container('user');

        if (!is_null($userContainer->authenticatedUser)) {
            $this->authenticatedUser = $userContainer->authenticatedUser;
        }

        return parent::onDispatch($e);
    }

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
    }
}