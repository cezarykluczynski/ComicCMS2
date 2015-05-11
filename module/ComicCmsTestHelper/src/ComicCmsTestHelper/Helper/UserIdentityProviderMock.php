<?php
/**
 * Trait used for injecting different roles into BjyAuthorize identity provider.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

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