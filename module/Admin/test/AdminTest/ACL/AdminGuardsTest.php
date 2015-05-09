<?php

namespace AdminTest\ACL;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;

class AdminGuardsTest extends AbstractHttpControllerTestCase
{
    /**
     * Helper for detecting console routes.
     * @return boolean
     */
    public function isConsoleRouting($controller, $action)
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
     * Checks config for any admin-related routes, if they are only accesible by role admin.
     * You can never be too sure with this.
     */
    public function testAdminRelatedControllersAreOnlyAccessibleByAdmin()
    {
        $config = $this->getApplication()->getServiceManager()->get('Config');

        if (!isset($config['bjyauthorize']['guards']) || empty($config['bjyauthorize']['guards'])) {
            $this->assertTrue(false, 'BjyAuthorize guards are missing.');

            return;
        }

        $guards = $config['bjyauthorize']['guards']['BjyAuthorize\Guard\Controller'];

        foreach($guards as $guard)
        {
            /** Non-admin controllers are not a subject of this test. */
            if (stripos($guard['controller'], 'Admin') === false || stripos($guard['controller'], 'Auth') !== false)
            {
                continue;
            }

            /** Console routing are not subject to ACL. */
            if ($this->isConsoleRouting($guard['controller'], $guard['action']))
            {
                continue;
            }

            if (count($guard['roles']) !== 1 || !in_array('admin', $guard['roles']))
            {
                $this->assertTrue(false, 'Controller '.$guard['controller'].'::'.$guard['action'].' is not secured.');
            }
        }
    }
}