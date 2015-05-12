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
     * Helper for detecting console routes.
     *
     * @param string $controller Controller name to check.
     * @param string $action     Action name to check.
     * @return boolean           True, is controller and action combined are accessed via console routing,
     *                           false otherwise.
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
     *
     * @coversNothing
     */
    public function testAdminRelatedControllersAreOnlyAccessibleByAdmin()
    {
        $config = $this->getApplication()->getServiceManager()->get('Config');

        if (!isset($config['bjyauthorize']['guards']) || empty($config['bjyauthorize']['guards'])) {
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
                $success = false;
                $this->assertTrue(false, 'Controller '.$guard['controller'].'::'.$guard['action'].' is not secured.');
            }
        }

        /** Dummy assertion, so PHPUnit won't mark this test as risky. */
        if ($success)
        {
            $this->assertTrue(true, 'Routes are secured.');
        }
    }
}