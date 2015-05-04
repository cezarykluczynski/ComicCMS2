<?php

namespace Comic\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Slug.
 *
 * @ORM\Entity
 * @ORM\Table(name="slugs")
 * @property string $slug
 * @property int $parentId
 * @property int $id
 */
class Slug
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
    protected $slug;

    /**
     * @var Role
     * @ORM\ManyToOne(targetEntity="\Comic\Entity\Slug")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    protected $parent;

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