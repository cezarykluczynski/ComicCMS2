<?php
/**
 * Tests for HTTP auth actions: signin in with correct in incorrect credentials, and sign out.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace AdminTest\Controller;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;
use Zend\Stdlib\Parameters;
use Zend\Session\Container;

class AuthControllerTestHttp extends AbstractHttpControllerTestCase
{
    /**
     * Sign in action can be accessed.
     */
    public function testSigninActionCanBeAccessed()
    {
        $this->dispatch('/admin/signin');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Admin');
        $this->assertControllerName('Admin\Controller\Auth');
        $this->assertControllerClass('AUthController');
        $this->assertMatchedRouteName('admin-signin');
    }

    /**
     * Invalid credentials generate an error for POST request.
     */
    public function testSigninActionRespondToInvalidCredentials()
    {
        $this->reset();
        $p = new Parameters();
        $p
            ->set('email','nope@example.com')
            ->set('password','justTooEasy');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($p);
        $this->dispatch('/admin/signin');
        $this->assertResponseStatusCode(401);

        $container = new Container('user');
        $this->assertNull($container->id, 'User session container not created.');

        $this->assertQuery('#email[value="nope@example.com"]');
        $this->assertQuery('#password[value="justTooEasy"]');
    }

    /**
     * Invalid credentials generate an error for AJAX request.
     */
    public function testSigninActionRespondToInvalidCredentialsAJAX()
    {
        $this->reset();
        $p = new Parameters();
        $p
            ->set('email','nope@example.com')
            ->set('password','justTooEasy');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setHeaders(\Zend\Http\Headers::fromString('X-Requested-With: XMLHttpRequest'));
        $this->getRequest()->setPost($p);
        $this->dispatch('/admin/signin');
        $this->assertResponseStatusCode(401);

        $container = new Container('user');
        $this->assertNull($container->id, 'User session container not created.');

        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $this->assertJsonStringEqualsJsonString($this->getResponse()->getBody(), '{"success": false}');
    }

    /**
     * Invalid password generate an error for POST request.
     */
    public function testSigninActionRespondToInvalidPassword()
    {
        $this->reset();
        $p = new Parameters();
        $p
            ->set('email','admin@example.com')
            ->set('password','justTooEasy');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($p);
        $this->dispatch('/admin/signin');
        $this->assertResponseStatusCode(401);

        $container = new Container('user');
        $this->assertNull($container->id, "User session container not created.");

        $this->assertQuery('#email[value="admin@example.com"]');
        $this->assertQuery('#password[value="justTooEasy"]');
    }

    /**
     * Invalid password generate an error for AJAX request.
     */
    public function testSigninActionRespondToInvalidPasswordAJAX()
    {
        $this->reset();
        $p = new Parameters();
        $p
            ->set('email','admin@example.com')
            ->set('password','justTooEasy');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setHeaders(\Zend\Http\Headers::fromString('X-Requested-With: XMLHttpRequest'));
        $this->getRequest()->setPost($p);
        $this->dispatch('/admin/signin');
        $this->assertResponseStatusCode(401);

        $container = new Container('user');
        $this->assertNull($container->id, "User session container not created.");

        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $this->assertJsonStringEqualsJsonString($this->getResponse()->getBody(), '{"success": false}');
    }

    /**
     * Valid credentials create a redirect and a user session container for POST request.
     */
    public function testSigninActionCreatesValidSessionAndARedirect()
    {
        $this->reset();
        $p = new Parameters();
        $p
            ->set('email','admin@example.com')
            ->set('password','password');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($p);
        $this->dispatch('/admin/signin');
        $this->assertResponseStatusCode(201);
        $this->assertRedirectTo('/admin');

        $container = new Container('user');
        $this->assertNotNull($container->id, "User ID stored in session.");

        /** Test if user session is preserved between.  */
        $this->reset(true);
        $this->dispatch('/admin/signin');
        $container = new Container('user');
        $this->assertNotNull($container->id, "User ID preserved in session.");
    }

    /**
     * Valid credentials create a redirect and a user session container for AJAX request.
     */
    public function testSigninActionCreatesValidSessionAndARedirectAJAX()
    {
        $this->reset();
        $p = new Parameters();
        $p
            ->set('email','admin@example.com')
            ->set('password','password');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($p);
        $this->dispatch('/admin/signin', null, array(), true);
        $this->assertResponseStatusCode(201);

        $container = new Container('user');
        $this->assertNotNull($container->id, "User ID stored in session.");

        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $this->assertJsonStringEqualsJsonString($this->getResponse()->getBody(),
            '{"success": true, "url": "/admin"}');
    }

    /**
     * Sign out removes user from session.
     */
    public function testSignoutActionRemovesUserFromSession()
    {
        $this->reset();
        $p = new Parameters();
        $p
            ->set('email','admin@example.com')
            ->set('password','password');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($p);
        $this->dispatch('/admin/signin');
        $this->assertResponseStatusCode(201);

        /** Test if user ID was stored in session. */
        $container = new Container('user');
        $this->assertNotNull($container->id, "User ID stored in session before sign out.");

        $this->dispatch('/admin/signout');

        /** Test if user ID was removed from session. */
        $container = new Container('user');
        $this->assertNull($container->id, "User ID removed from session after sign out.");
    }

    /**
     * Sign out removes redirects to sign in form.
     */
    public function testSignoutActionedirectsToSignInForm()
    {
        $this->dispatch('/admin/signout');
        $this->assertRedirectTo('/admin/signin');
    }
}
