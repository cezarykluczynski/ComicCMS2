<?php

namespace ComicTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use User\Provider\Identity\UserIdentityProviderMockInterface;
use ApplicationTest\Fixture\FixtureProvider;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    use UserIdentityProviderMockInterface;
    use FixtureProvider;

    public function setUp()
    {
        $this->setApplicationConfig(include 'config/application.config.php');
        parent::setUp();
    }

    public function testIndexActionShowsInvitationForWhenTheresNoComics()
    {
        $this->reset();
        $this->grantAllRolesToUser();

        $this->dispatch('/admin/comic/widget/index');
        $this->assertResponseStatusCode(200);
        $this->assertTemplateName('comic/admin/widget/empty');

        $this->setOriginalUserRoles();
    }

    public function testIndexActionShowsFiveComicsWhenThereAreMoreThanFiveComics()
    {
        $this->reset();
        $this->grantAllRolesToUser();
        $this->addFixtures('ComicTest\Fixture\MultipleEntities');

        $this->dispatch('/admin/comic/widget/index');
        $this->assertResponseStatusCode(200);
        $this->assertTemplateName('comic/admin/widget/index');
        $this->assertQueryCount('div.comic', 5);

        $this->removeFixtures();
        $this->setOriginalUserRoles();
    }
}