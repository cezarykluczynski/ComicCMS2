<?php
/**
 * Authentication trait.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Application\Service;

use Zend\Session\Container;
use Zend\Mvc\MvcEvent;

trait Authentication
{
    /**
     * @var \User\Entity\User|null
     */
    protected $authenticatedUser;

    public function onDispatchAuthentication(MvcEvent $e)
    {
        $userContainer = new Container('user');

        if (!is_null($userContainer->authenticatedUser)) {
            $this->authenticatedUser = $userContainer->authenticatedUser;
        }
    }
}