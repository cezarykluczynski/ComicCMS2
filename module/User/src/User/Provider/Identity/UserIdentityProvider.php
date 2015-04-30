<?php

namespace User\Provider\Identity;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use BjyAuthorize\Provider\Identity\ProviderInterface;
use Zend\Session\Container;

class UserIdentityProvider implements
    ProviderInterface,
    ServiceLocatorAwareInterface
{
    /**
     * @var string
     */
    protected $defaultRole;

    public function __construct() {
    }

    protected $serviceLocator;
    protected $authenticatedUser;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function getIdentityRoles()
    {

        $config = $this->getServiceLocator()->get('Config');
        $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        /** Set default role from BjyAuthorize config. */
        if (isset($config['bjyauthorize']['default_role'])) {
            $this->setDefaultRole($config['bjyauthorize']['default_role']);
        }

        $userContainer = new Container('user');

        if (!is_null($userContainer->id)) {
            $this->authenticatedUser = $this
            ->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default')
            ->getRepository('User\Entity\User')
            ->findOneById($userContainer->id);
        }

        if (!is_null($this->authenticatedUser)) {
            /** Inject authenticated user into session for controllers to use. */
            $userContainer->authenticatedUser = $this->authenticatedUser;


            $roles = $this->authenticatedUser->getRoles();

            $roleNames = $roles->map(function ($role) {
                return $role->getRoleId();
            });

            return $roleNames->toArray();
        }

        return array($this->getDefaultRole());
    }

    /**
     * @return string
     */
    public function getDefaultRole()
    {
        return $this->defaultRole;
    }

    /**
     * @param string
     */
    public function setDefaultRole($defaultRole)
    {
        $this->defaultRole = $defaultRole;
    }
}
