<?php
/**
 * Tests for user repository.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace UserTest\Controller\Admin;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;

/**
 * @coversDefaultClass \User\Entity\UserRepository
 * @uses \Application\Controller\ApplicationController
 * @uses \User\Provider\Identity\UserIdentityProvider
 * @uses \User\Provider\Identity\UserIdentityProviderMock
 */
class UserRepositoryTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->getEntityManager();
    }

    /**
     * @covers ::paginate
     * @covers ::count
     */
    public function testUserReposityPaginator()
    {
        $userRepository = $this->em->getRepository('User\Entity\User');

        $this->reset();
        $this->grantAllRolesToUser();
        $this->loadFixtures('UserTest\Fixture\Users', array(
            'count' => 10,
        ));

        $count = $userRepository->count();

        $pagination = $userRepository->paginate([
            'offset' => $count - 10,
            'limit' => 2,
        ]);

        $this->assertEquals('john0@example.com', $pagination[0]['email']);
        $this->assertEquals('john1@example.com', $pagination[1]['email']);
        $this->assertEquals(2, count($pagination));

        $pagination = $userRepository->paginate([
            'offset' => $count - 6,
            'limit' => 1,
        ]);

        $this->assertEquals('john4@example.com', $pagination[0]['email']);
        $this->assertEquals(1, count($pagination));

        $this->removeFixtures();
        $this->revokeGrantedRoles();
    }

    /**
     * @covers ::paginate
     */
    public function testUserReposityPaginatorThrowsExceptionForMissingLimitOption()
    {
        $userRepository = $this->em->getRepository('User\Entity\User');

        $this->setExpectedException('InvalidArgumentException');
        $userRepository->paginate([
            'offset' => 1,
        ]);
    }

    /**
     * @covers ::paginate
     */
    public function testUserReposityPaginatorThrowsExceptionForLimitOptionNotBeingAPositiveInteger()
    {
        $userRepository = $this->em->getRepository('User\Entity\User');

        $this->setExpectedException('InvalidArgumentException');
        $userRepository->paginate([
            'limit' => 0,
        ]);
    }

    /**
     * @covers ::paginate
     */
    public function testUserReposityPaginatorThrowsExceptionForMissingOffsetOption()
    {
        $userRepository = $this->em->getRepository('User\Entity\User');

        $this->setExpectedException('InvalidArgumentException');
        $userRepository->paginate([
            'limit' => 1,
        ]);
    }

    /**
     * @covers ::paginate
     */
    public function testUserReposityPaginatorThrowsExceptionForNegativeOffsetOption()
    {
        $userRepository = $this->em->getRepository('User\Entity\User');

        $this->setExpectedException('InvalidArgumentException');
        $userRepository->paginate([
            'limit' => -1,
        ]);
    }
}