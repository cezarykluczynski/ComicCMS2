<?php
/**
 * This class provides user identity to BjyAuthorize by exposing logged in user roles.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace User\Provider\Identity;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use BjyAuthorize\Provider\Identity\ProviderInterface;
use Zend\Session\Container;

class UserIdentityProvider implements
    ProviderInterface,
    ServiceLocatorAwareInterface
{
    /** @var string */
    protected $defaultRole;

    protected $serviceLocator;

    protected $authenticatedUser;

    protected $entityManager;

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
            $this->setAuthenticatedUser($userContainer->id);
        }

        if ($this->userIsAuthenticated()) {
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

    protected function setAuthenticatedUser($userId)
    {
        $this->authenticatedUser = $this
            ->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default')
            ->getRepository('User\Entity\User')
            ->findOneById($userId);
    }

    protected function getAuthenticatedUser()
    {
        return $this->authenticatedUser;
    }

    protected function userIsAuthenticated() {
        return !is_null($this->getAuthenticatedUser());
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
