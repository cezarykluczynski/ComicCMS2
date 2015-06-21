<?php
/**
 * Image entity.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Asset\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Image.
 *
 * @ORM\Entity(repositoryClass="\Asset\Entity\ImageRepository")
 * @ORM\EntityListeners({"\Asset\Entity\ImageListener"})
 * @ORM\Table(name="images")
 * @property int $id
 */
class Image
{
    protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="canonical_relative_path", length=80);
     */
    protected $canonicalRelativePath;

    /**
     * @ORM\Column(type="integer");
     */
    protected $width;

    /**
     * @ORM\Column(type="integer");
     */
    protected $height;

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