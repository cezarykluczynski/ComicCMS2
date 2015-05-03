<?php

namespace User\Provider\Identity;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use BjyAuthorize\Provider\Identity\ProviderInterface;
use Zend\Session\Container;

class UserIdentityProviderMock extends UserIdentityProvider
{
    public static $mockedRoles;

    public function getIdentityRoles()
    {
        return is_null(self::$mockedRoles) ? parent::getIdentityRoles() : self::$mockedRoles;
    }
}