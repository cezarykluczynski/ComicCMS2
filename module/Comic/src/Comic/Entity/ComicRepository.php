<?php
/**
 * Comic entity repository.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

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
     * @todo Implement hydrator.
     */
    public function create($data = array())
    {
        /** @var \Comic\Entity\Slug Newly created slug, or passed from controller. */
        $slug = isset($data['slugEntity']) ? $data['slugEntity'] : new Slug;
        $slug->slug = isset($data['slug']['slug']) ? $data['slug']['slug'] : '';
        $this->_em->persist($slug);

        /** @var \Comic\Entity\Comic */
        $comic = new Comic;
        $comic->slug = $slug;
        $comic->title = isset($data['title']) ? $data['title'] : '';
        $comic->description = isset($data['description']) ? $data['description'] : '';
        $comic->tagline = isset($data['tagline']) ? $data['tagline'] : '';
        $comic->author = isset($data['author']) ? $data['author'] : '';
        $this->_em->persist($comic);

        $this->_em->flush();

        return $comic;
    }

    /**
     * Return list of all comic entities.
     *
     * @return array
     */
    public function getList()
    {
        $results = $this->_em->createQueryBuilder()
            ->select('Comic', 'Slug')
            ->from('Comic\Entity\Comic', 'Comic')
            ->leftJoin('Comic.slug', 'Slug')
            ->orderBy('Comic.id', 'DESC')
            ->getQuery()
            ->getArrayResult();

        foreach($results as $result)
        {
            $result['slug'] = [
                'id' => isset($result['slug']['id']) ? $result['slug']['id'] : null,
                'slug' => isset($result['slug']['slug']) ? $result['slug']['slug'] : '',
            ];
        }

        return $results;
    }
}