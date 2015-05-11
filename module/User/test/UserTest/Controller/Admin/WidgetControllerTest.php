<?php
/**
 * Tests for controller for user functionalities in admin panel.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace UserTest\Controller\Admin;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;
use Zend\Json\Json;

/**
 * @coversDefaultClass \User\Controller\Admin\WidgetController
 * @uses \Application\Controller\ApplicationController
 * @uses \User\Entity\UserRepository
 * @uses \User\Provider\Identity\UserIdentityProvider
 * @uses \User\Provider\Identity\UserIdentityProviderMock
 */
class WidgetControllerTest extends AbstractHttpControllerTestCase
{
    /**
     * @covers ::indexAction
     */
    public function testUserAdminWidgetIndexCanBeAccessed()
    {
        $this->grantAllRolesToUser();

        $this->dispatch('/admin/user/widget/index');
        $this->assertResponseStatusCode(200);
        $this->assertTemplateName('user/admin/widget/index');

        $this->revokeGrantedRoles();
    }

    /**
     * @covers ::usersAction
     */
    public function testUsersCanBeRetrievedAsJSON()
    {
        $this->grantAllRolesToUser();
        $this->loadFixtures('UserTest\Fixture\Users', array(
            'count' => 10,
        ));

        $userRepository = $this->em->getRepository('User\Entity\User');
        $count = $userRepository->count();

        $page = (ceil($count / 10) - 1);

        $this->dispatch('/admin/user/widget/users?limit=2&page=' . $page);

        /**
         * Test only if response is JSON and JSON has 2 items. UserRepository has it own tests in
         * {@link \UserTest\Controller\Admin\UserRepositoryTest::testUserReposityPaginator()}.
         */
        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $response = Json::decode($this->getResponse()->getContent(), true);
        $this->assertEquals(2, count($response['users']));

        $this->removeFixtures();
        $this->revokeGrantedRoles();
    }
}