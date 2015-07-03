<?php
/**
 * Fixtures for comic entity with single strip with image.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicTest\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ComicCmsTestHelper\Fixture\FixtureRepository;
use Comic\Entity\Comic as ComicEntity;
use Comic\Entity\Strip;
use Comic\Entity\StripImage;
use Asset\Entity\Image;

class ComicWithStripWithImage extends FixtureRepository
{
    protected $comic;
    protected $strip;
    protected $stripImage;
    protected $image;

    /**
     * Loads a single comic with a 10 strips.
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        /** @var \Asset\Entity\Image */
        $this->image = new Image;
        $this->image->canonicalRelativePath = 'canonical/relative/path.png';

        /** @var \Comic\Entity\StripImage */
        $this->stripImage = new StripImage;
        $this->stripImage->position = 0;
        $this->stripImage->caption = 'Image caption';

        /** @var \Comic\Entity\Strip */
        $this->strip = new Strip;
        $this->strip->title = 'Strip title';

        /** @var \Comic\Entity\Comic */
        $this->comic = new ComicEntity;
        $this->comic->title = 'Comic';

        /** Bind entities together. */
        $this->stripImage->image = $this->image;
        $this->stripImage->strip = $this->strip;
        $this->strip->comic = $this->comic;
        $this->strip->addImage($this->stripImage);
        $this->comic->addStrip($this->strip);

        /** Save references, so entities can be retrieved or deleted. */
        $this->entities[] = $this->image;
        $this->entities[] = $this->stripImage;
        $this->entities[] = $this->strip;
        $this->entities[] = $this->comic;

        /** Save everything. */
        foreach($this->entities as $entity)
        {
            $this->manager->persist($entity);
        }
        $this->manager->flush();
    }
}