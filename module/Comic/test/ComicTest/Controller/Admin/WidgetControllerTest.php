<?php
/**
 * Tests for controller for comic functionalities in admin panel.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicTest\Controller\Admin;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;

class WidgetControllerTest extends AbstractHttpControllerTestCase
{
    /**
     * Test if correct template is show for when theres no comics.
     */
    public function testComicAdminWidgetIndexActionShowsInvitationForWhenTheresNoComics()
    {
        $this->reset();
        $this->grantAllRolesToUser();

        $this->dispatch('/admin/comic/widget/index');
        $this->assertResponseStatusCode(200);
        $this->assertTemplateName('comic/admin/widget/empty');

        $this->revokeGrantedRoles();
    }

    /**
     * Test if correct template is shown for when there's more than five comics.
     */
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