<?php

namespace ComicTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use User\Provider\Identity\UserIdentityProviderMockInterface;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    use UserIdentityProviderMockInterface;

    public function setUp()
    {
        $this->setApplicationConfig(include 'config/application.config.php');
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testIndexActionShowsInvitationForWhenTheresNoComics()
    {
        $this->reset();
        $this->grantAllRolesToUser();

        $this->dispatch('/admin/comic/widget/index');
        $this->assertResponseStatusCode(200);

        $this->setOriginalUserRoles();
    }
}