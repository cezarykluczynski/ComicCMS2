<?php
/**
 * Comic entity.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Comic\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Comic.
 *
 * @ORM\Entity(repositoryClass="\Comic\Entity\ComicRepository")
 * @ORM\Table(name="comics")
 */
class Comic
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $tagline;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $author;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @ORM\OneToOne(targetEntity="\Assets\Entity\Image")
     * @ORM\JoinColumn(name="logo_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $logo;

    /**
     * @ORM\OneToOne(targetEntity="\Comic\Entity\Slug", inversedBy="comic", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="slug_id", referencedColumnName="id")
     */
    protected $slug;

    /**
     * @ORM\OneToOne(targetEntity="\User\Entity\Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=true)
     */
    protected $role;

    /**
     * @ORM\ManyToMany(targetEntity="\Comic\Entity\Strip", fetch="LAZY")
     * @ORM\JoinTable(name="comics_strips",
     *      joinColumns={@ORM\JoinColumn(name="comic_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="strip_id", referencedColumnName="id")}
     *      )
     */
    private $strips;

    /**
     * @ORM\ManyToMany(targetEntity="\Comic\Entity\Slug", fetch="LAZY")
     * @ORM\JoinTable(name="comics_slugs",
     *      joinColumns={@ORM\JoinColumn(name="comic_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="slug_id", referencedColumnName="id")}
     *      )
     */
    private $slugs;

    public function __construct() {
        $this->strips = new ArrayCollection();
        $this->slugs = new ArrayCollection();
    }

    public function addStrip(Strip $strip)
    {
        $this->strips[] = $strip;
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

    public function getStrips()
    {
        return $this->strips;
    }

    public function getSlugs()
    {
        return $this->slugs;
    }
}