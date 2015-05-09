<?php

namespace ComicCmsTestHelper\Helper;

use User\Provider\Identity\UserIdentityProviderMock as Mock;

trait UserIdentityProviderMock
{
    public function grantAllRolesToUser() {
        Mock::$mockedRoles = array('guest', 'user', 'admin');
    }

    public function revokeGrantedRoles()
    {
        Mock::$mockedRoles = null;
    }
}