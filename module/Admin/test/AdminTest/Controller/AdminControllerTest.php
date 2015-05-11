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

class AdminControllerTest extends AbstractHttpControllerTestCase
{
    /**
     * Index action can't be accessed.
     * Process isolation is required so exit() wouldn't break PHPUnit.
     * @runInSeparateProcess
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
        $this->dispatch('/admin/signin');

        /** Go to admin view next. */
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