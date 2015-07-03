<?php
/**
 * Strip entity.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Comic\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Strip.
 *
 * @ORM\Entity(repositoryClass="\Comic\Entity\StripRepository")
 * @ORM\Table(name="strips")
 * @property int $id
 */
class Strip
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255);
     */
    protected $title;

    /**
     * @ORM\Column(type="string");
     */
    protected $caption;
    /**
     * @ORM\OneToMany(targetEntity="\Comic\Entity\StripImage", mappedBy="strip")
     * @ORM\OrderBy({"position" = "ASC"})
     **/
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="Comic\Entity\Comic", inversedBy="strips", cascade={"persist"})
     * @ORM\JoinColumn(name="comic_id", referencedColumnName="id")
     **/
    protected $comic;

    public function __construct() {
        $this->images = new ArrayCollection();
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


    public function addImage(StripImage $image)
    {
        $this->images[] = $image;
    }

    public function getFirstImageCanonicalRelativePath()
    {
        $firstStripImage = $this->images->count() ? $this->images->first() : null;
        return $firstStripImage ? $firstStripImage->image->canonicalRelativePath : null;
    }
}