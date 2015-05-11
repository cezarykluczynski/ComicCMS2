<?php
/**
 * User entity.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User.
 * Simple entity classes are excluded from coverage reports, for now.
 *
 * @ORM\Entity(repositoryClass="\User\Entity\UserRepository")
 * @ORM\Table(name="users")
 * @property string $email
 * @property string $password
 * @property array $roles
 * @property int $id
 * @codeCoverageIgnore
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\ManyToMany(targetEntity="\User\Entity\Role")
     * @ORM\JoinTable(name="user_role_linker",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     **/
    private $roles;

    public function __construct() {
        $this->roles = new ArrayCollection();
    }

    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * Returns all user roles.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * Returns all user roles as a flatten array.
     *
     * @return array
     */
    public function getRolesIdsAsArray() {
        $rolesIds = [];

        foreach($this->getRoles() as $role)
        {
            $rolesIds[] = $role->getRoleId();
        }

        return $rolesIds;
    }
}