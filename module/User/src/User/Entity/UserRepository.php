<?php
/**
 * User entity.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace User\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * Counts all users.
     *
     * @return integer Total number of users.
     */
    public function count()
    {
        return $this->_em
            ->createQuery('SELECT COUNT(u.id) FROM \User\Entity\User u')
            ->getSingleScalarResult();
    }

    /**
     * Returns a collection with pagination options applied.
     *
     * @param $options                  Options to apply to collection, mainly 'page' and 'offset'.
     * @return array                    Array of arrays containing user data.
     * @throws InvalidArgumentException If the options array does not contain a numeric limit option.
     * @throws InvalidArgumentException If the options array does not contain a numeric offset option.
     * */
    public function paginate(array $options)
    {
        if (!isset($options['limit']) || !is_int($options['limit']) || $options['limit'] < 1)
        {
            $msg = __CLASS__ . '::' . __FUNCTION__ .' require \'limit\' option that is a positive integer.';
            throw new \InvalidArgumentException($msg);
        }

        if (!isset($options['offset']) || !is_int($options['offset']) || $options['offset'] < 0)
        {
            $msg = __CLASS__ . '::' . __FUNCTION__ .' require \'offset\' option that is integer.';
            throw new \InvalidArgumentException($msg);
        }

        /** @var array */
        $usersCollection = $this
            ->findBy([], ['id' => 'asc'], $options['limit'], $options['offset']);

        /** @var array */
        $users = [];

        foreach($usersCollection as $user)
        {
            $users[] = [
                'id' => $user->id,
                'email' => $user->email,
                'roles' => $user->getRolesIdsAsArray(),
            ];
        }

        return $users;
    }
}
