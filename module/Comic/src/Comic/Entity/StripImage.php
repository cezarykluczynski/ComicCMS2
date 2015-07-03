<?php
/**
 * Strip image entity.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Comic\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Strip image.
 *
 * @ORM\Entity
 * @ORM\Table(name="strip_images")
 * @property int $id
 */
class StripImage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $caption;

    /**
     * @ORM\Column(type="integer");
     */
    protected $position;

    /**
     * @ORM\ManyToOne(targetEntity="\Comic\Entity\Strip", inversedBy="images", cascade={"persist"})
     * @ORM\JoinColumn(name="strip_id", referencedColumnName="id")
     **/
    private $strip;

    /**
     * @ORM\OneToOne(targetEntity="\Asset\Entity\Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     **/
    private $image;

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
}