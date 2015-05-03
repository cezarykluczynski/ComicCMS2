<?php

namespace User\Provider\Identity;

trait UserIdentityProviderMockInterface
{
    public function grantAllRolesToUser() {
        UserIdentityProviderMock::$mockedRoles = array('guest', 'user', 'admin');
    }

    public function setOriginalUserRoles()
    {
        UserIdentityProviderMock::$mockedRoles = null;
    }
}