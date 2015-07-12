<?php
/**
 * Strip entity repository.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Comic\Entity;

use Doctrine\ORM\EntityRepository;

class StripRepository extends EntityRepository
{
    public function createFromPost($data, Comic $comic)
    {
        list($imagesIds, $captions, $images, $positions) = $this->getImagesFromPost($data);

        $strip = new Strip;
        $strip->title = $data['title'];
        $strip->caption = $data['caption'];
        $strip->comic = $comic;

        foreach($images as $image)
        {
            $stripImage = new StripImage;
            $stripImage->image = $image;
            $stripImage->strip = $strip;
            $stripImage->position = $positions[$image->id];
            $stripImage->caption = $captions[$image->id];
            $this->_em->persist($stripImage);
        }

        $this->_em->persist($strip);
        $this->_em->flush();

        return $strip;
    }

    public function updateFromPost($data, Strip $strip)
    {
        list($imagesIds, $captions, $images, $positions) = $this->getImagesFromPost($data);

        $strip->title = $data['title'];
        $strip->caption = $data['caption'];

       // var_dump('post image', $data['images'][0]['entity']['id']);

        $stripImages = [];

        /** Removed deleted images. */
        foreach($strip->images as $stripImage)
        {
            if (!in_array($stripImage->image->id, $imagesIds))
            {
                $this->_em->remove($stripImage);
            }
            else
            {
                $stripImages[$stripImage->image->id] = $stripImage;
            }
        }

        foreach($images as $image)
        {
            if (isset($stripImages[$image->id]))
            {
                $stripImage = $stripImages[$image->id];
            }
            else
            {
                $stripImage = new StripImage;
                $stripImage->image = $image;
                $stripImage->strip = $strip;
            }

            $stripImage->caption = $captions[$image->id];
            $stripImage->position = $positions[$image->id];

            $stripImage->strip = $strip;

            $this->_em->persist($stripImage);
            $strip->addImage($stripImage);
        }

        $this->_em->persist($strip);
        $this->_em->flush();

        return $strip;
    }

    protected function getImagesFromPost($data)
    {
        $imagesIds = [];
        $captions = [];
        $positions = [];
        $position = 0;

        foreach($data['images'] as $image)
        {
            $imagesIds[] = (int) $image['entity']['id'];
            $captions[$image['entity']['id']] = (string) $image['caption'];
            $positions[$image['entity']['id']] = $position;
            $position++;
        }

        $images = $this->_em->getRepository('Asset\Entity\Image')->findBy([
            'id' => $imagesIds,
        ]);

        return [$imagesIds, $captions, $images, $positions];
    }

    /**
     * Counts all strips for a given comic.
     *
     * @return integer Total number of strips for a given comic.
     */
    public function count(Comic $comic)
    {
        return $this->_em
            ->createQuery('SELECT COUNT(s.id) FROM \Comic\Entity\Strip s WHERE s.comic = :comic')
            ->setParameter('comic', $comic)
            ->getSingleScalarResult();
    }
}