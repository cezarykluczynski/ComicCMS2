<?php

namespace ComicTest\Controller\Admin;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;

class WidgetControllerTest extends AbstractHttpControllerTestCase
{
    public function testComicAdminWidgetIndexActionShowsInvitationForWhenTheresNoComics()
    {
        $this->reset();
        $this->grantAllRolesToUser();

        $this->dispatch('/admin/comic/widget/index');
        $this->assertResponseStatusCode(200);
        $this->assertTemplateName('comic/admin/widget/empty');

        $this->revokeGrantedRoles();
    }

    public function testComicAdminWidgetIndexActionShowsFiveComicsWhenThereAreMoreThanFiveComics()
    {
        $this->reset();
        $this->grantAllRolesToUser();
        $this->loadFixtures('ComicTest\Fixture\Comics');

        $this->dispatch('/admin/comic/widget/index');
        $this->assertResponseStatusCode(200);
        $this->assertTemplateName('comic/admin/widget/index');
        $this->assertQueryCount('div.comic', 5);

        $this->removeFixtures();
        $this->revokeGrantedRoles();
    }
}