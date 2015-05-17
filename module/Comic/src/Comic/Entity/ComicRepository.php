<?php

namespace Comic\Entity;

use Doctrine\ORM\EntityRepository;
use Comic\Entity\Slug;

class ComicRepository extends EntityRepository
{
    /**
     * Creates a new comic entity with given data.
     *
     * @param $data New comic data, at least title and slug.
     * @return \Comic\Entity\Comic|null Comic entity on success, null otherwise.
     */
    public function create($data = array())
    {
        /** @var \Comic\Entity\Slug */
        $slug = new Slug;
        $slug->slug = isset($data['slug']) ? $data['slug'] : '';
        $this->_em->persist($slug);

        /** @var \Comic\Entity\Comic */
        $comic = new Comic;
        $comic->slug = $slug;
        $comic->title = isset($data['title']) ? $data['title'] : '';
        $comic->description = isset($data['description']) ? $data['description'] : '';
        $comic->tagline = isset($data['tagline']) ? $data['tagline'] : '';
        $this->_em->persist($comic);

        $this->_em->flush();

        return $comic;
    }
}