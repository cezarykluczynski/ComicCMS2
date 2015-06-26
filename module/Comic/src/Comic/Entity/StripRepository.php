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
        $imagesIds = [];
        $captions = [];

        foreach($data['images'] as $image)
        {
            $imagesIds[] = (int) $image['entity']['id'];
            $captions[$image['entity']['id']] = (string) $image['caption'];
        }

        $images = $this->_em->getRepository('Asset\Entity\Image')->findBy([
            'id' => $imagesIds,
        ]);

        $strip = new Strip;
        $strip->title = $data['title'];
        $strip->caption = $data['caption'];
        $strip->comic = $comic;

        $position = 0;
        foreach($images as $image)
        {
            $stripImage = new StripImage;
            $stripImage->image = $image;
            $stripImage->strip = $strip;
            $stripImage->position = $position;
            $stripImage->caption = $captions[$image->id];
            $this->_em->persist($stripImage);

            $position++;
        }

        $this->_em->persist($strip);
        $this->_em->flush();

        return $strip;
    }
}