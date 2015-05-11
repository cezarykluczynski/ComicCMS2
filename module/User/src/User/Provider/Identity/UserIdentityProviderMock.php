<?php
/**
 * This class act as a replacement for {@link \User\Provider\Identity\UserIdentityProvider} during PHPUnit tests.
 * It keeps original behaviour of the parent class, except for when trait
 * {@link ComicCmsTestHelper\Helper\UserIdentityProviderMock} njects differents roles into it.
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

class UserIdentityProviderMock extends UserIdentityProvider
{
    /** @var null|array */
    public static $mockedRoles;

    public function getIdentityRoles()
    {
        return is_null(self::$mockedRoles) ? parent::getIdentityRoles() : self::$mockedRoles;
    }
}