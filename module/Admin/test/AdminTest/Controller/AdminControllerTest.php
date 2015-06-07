<?php
/**
 * Test admin panel accessibility for authenticated and non authenticated user.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace AdminTest\Controller;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;
use Zend\Stdlib\Parameters;
use Zend\Mvc\MvcEvent;
use BjyAuthorize\Guard\Controller;

/**
 * @coversDefaultClass \Admin\Controller\AdminController
 * @uses \Application\Controller\AbstractActionController
 * @uses \Application\Service\Authentication
 * @uses \Application\Service\Database
 * @uses \User\Provider\Identity\UserIdentityProvider
 * @uses \User\Provider\Identity\UserIdentityProviderMock
 */
class AdminControllerTest extends AbstractHttpControllerTestCase
{
    /**
     * Index action can't be accessed.
     * Process isolation is required so exit() wouldn't break PHPUnit.
     * This test covers nothing, because it's BjyAuthorize that should work before controller is dispatched.
     *
     * @runInSeparateProcess
     * @coversNothing
     */
    public function testAdminIndexActionCantBeAccessed()
    {
        $self = $this;
        $this->reset();

        $this->getApplication()
            ->getEventManager()
            ->attach(MvcEvent::EVENT_DISPATCH_ERROR, function($event) use($self) {
                /** Don't assert response status code, assert distpatch error instead.
                 *  because we're at a point were response status is still 200. */
                $self->assertEquals($event->getError(), Controller::ERROR);

                $this->assertModuleName('Admin');
                $this->assertControllerName('Admin\Controller\Admin');
                $this->assertControllerClass('AdminController');
                $this->assertMatchedRouteName('admin-index');

                /** Exit before uncatchable (I think) error makes this test fail. */
                exit;
            }, 10);
        $this->dispatch('/admin');
        $this->assertTrue(false, 'Dispatch error not generated, admin panel can be accessed.');
    }

    /**
     * Index action can be accessed after authentication.
     *
     * @covers ::indexAction
     * @covers ::getWidgets
     * @uses \Admin\Controller\AuthController
     * @uses \Application\View\Helper\AngularTemplates
     */
    public function testAdminIndexActionCanBeAccessedAfterAuthentication()
    {
        $this->reset();

        $p = new Parameters();
        $p
            ->set('email','admin@example.com')
            ->set('password','password');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($p);

        /** Sign in successfully. */
        $this->dispatch('/admin/auth/signin');

        /** NExt, go to admin view. */
        $this->reset(true);
        $this->getRequest()->setMethod('GET');
        $this->dispatch('/admin');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Admin');
        $this->assertControllerName('Admin\Controller\Admin');
        $this->assertControllerClass('AdminController');
        $this->assertMatchedRouteName('admin-index');
    }
}