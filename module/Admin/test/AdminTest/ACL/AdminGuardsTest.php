<?php
/**
 * Tests. They will make sure that no admin route, except auth, can by accessed by user without admin role.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace AdminTest\ACL;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;

class AdminGuardsTest extends AbstractHttpControllerTestCase
{
    /**
     * Checks config for any admin-related routes, if they are only accesible by role admin.
     * You can never be too sure with this.
     *
     * @coversNothing
     */
    public function testAdminRelatedControllersAreOnlyAccessibleByAdmin()
    {
        $config = $this->getApplication()->getServiceManager()->get('Config');

        if (!$this->configHasGuards($config)) {
            $this->assertTrue(false, 'BjyAuthorize guards are missing.');

            return;
        }

        /** @var array */
        $guards = $config['bjyauthorize']['guards']['BjyAuthorize\Guard\Controller'];

        /** @var boolean */
        $success = true;

        foreach($guards as $guard)
        {
            /** Non-admin controllers are not a subject of this test. */
            if (!$this->isAdminController($guard['controller']))
            {
                continue;
            }
            /** Console routing are not subject to ACL. */
            if ($this->isConsoleRouting($guard['controller'], $guard['action']))
            {
                continue;
            }

            if (!$this->hasAdminRole($guard['roles']))
            {
                $success = false;
                $this->assertTrue(false, 'Controller '.$guard['controller'].'::'.$guard['action'].' is not secured.');
            }
        }

        /** Dummy assertion, so PHPUnit won't mark this test as risky because of no assertions. */
        if ($success)
        {
            $this->assertTrue(true, 'Routes are secured.');
        }
    }

    /**
     * Helper for checking if config has at least non-empty array of BjyAuthorize guards.
     *
     * @param array $config Global config.
     * @return boolean
     */
    protected function configHasGuards($config)
    {
        return isset($config['bjyauthorize']['guards']) && !empty($config['bjyauthorize']['guards']);
    }

    /**
     * Helper for checking if controller is non admin. That includes all controllers without admin in name,
     * and also admin controllers related to auth.
     *
     * @param string $controller Controller name.
     * @return boolean
     */
    protected function isAdminController($controller)
    {
        return stripos($controller, 'Admin') !== false && stripos($controller, 'Auth') === false;
    }

    /**
     * Helper for detecting console routes.
     *
     * @param string $controller Controller name to check.
     * @param string $action     Action name to check.
     * @return boolean           True, is controller and action combined are accessed via console routing,
     *                           false otherwise.
     */
    protected function isConsoleRouting($controller, $action)
    {
        $config = $this->getApplication()->getServiceManager()->get('Config');

        foreach($config['console']['router']['routes'] as $consoleRoute)
        {
            if (isset($consoleRoutes['options']['default']))
            {
                $currentConfig = $consoleRoutes['options']['default'];
                if ($controller === $currentConfig['controller'] && $action === $currentConfig['action'])
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Helper for checking if roles array contain "admin" role.
     *
     * @param array $roles List of roles.
     * @return boolean
     */
    protected function hasAdminRole($roles)
    {
        return in_array('admin', $roles);
    }
}