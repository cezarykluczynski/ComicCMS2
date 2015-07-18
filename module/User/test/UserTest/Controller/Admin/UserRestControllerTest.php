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
 * @coversDefaultClass \User\Controller\UserRestController
 * @uses \Application\Controller\AbstractActionController
 * @uses \Application\Service\Authentication
 * @uses \Application\Service\Database
 * @uses \Application\Service\Dispatcher
 * @uses \Settings\Service\Settings
 * @uses \User\Entity\UserRepository
 * @uses \User\Provider\Identity\UserIdentityProvider
 * @uses \User\Provider\Identity\UserIdentityProviderMock
 */
class UserRestControllerTest extends AbstractHttpControllerTestCase
{
    /**
     * @covers ::getList
     */
    public function testUsersListCanBeObtained()
    {
        $this->grantAllRolesToUser();
        $this->loadFixtures('UserTest\Fixture\Users', array(
            'count' => 10,
        ));

        $userRepository = $this->em->getRepository('User\Entity\User');
        $count = $userRepository->count();
        $offset = $count - 2;

        $this->dispatch('/rest/user?limit=2&offset=' . $offset);

        $response = Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);

        /**
         * Test only if response is JSON and JSON has 2 items. UserRepository has it own tests in
         * {@link \UserTest\Controller\Admin\UserRepositoryTest::testUserReposityPaginator()}.
         */
        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $this->assertEquals(2, count($response['users']));

        $this->removeFixtures();
        $this->revokeGrantedRoles();
    }
}