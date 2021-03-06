<?php
/**
 * Fixtures for user entity.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace UserTest\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ComicCmsTestHelper\Fixture\FixtureRepository;
use User\Entity\User;

class Users extends FixtureRepository
{
    protected $entityClass = 'User\Entity\User';

    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $users = [];

        for($i = 0; $i < $this->options['count']; $i++)
        {
            $users[] = array(
                'email' => 'john' . $i . '@example.com',
                'password' => '$2a$10$VHbVa7ygXJQ7DCNyVLsl5u3PQWEJ11Hs0qAgjVIjN0zMJAaoTob4a',
            );
        }

        foreach($users as $user)
        {
            $entity = $this->produceUserEntity($user);
            $this->manager->persist($entity);
        }

        $this->manager->flush();
    }

    /**
    * Produces an instance of User object, with given login and email.
    *
    * @param array $user Array containing user data: email, login, and password.
    * @return \User\Entity\User
    */
    public function produceUserEntity($user)
    {
        $userEntity = new User();
        $this->entities[] = $userEntity;
        $userEntity->email = $user['email'];
        $userEntity->password = $user['password'];
        return $userEntity;
    }
}