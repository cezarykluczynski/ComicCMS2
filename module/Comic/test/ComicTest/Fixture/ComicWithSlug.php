<?php
/**
 * Fixtures for multiple comic entities.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicTest\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use ComicCmsTestHelper\Fixture\FixtureRepository;
use Comic\Entity\Comic as ComicEntity;
use Comic\Entity\Slug;

class ComicWithSlug extends FixtureRepository
{
    protected $comic;
    protected $slug;

    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->comic = new ComicEntity();
        $this->comic->title = 'Comic with slug';

        $this->slug = new Slug;
        $this->slug->slug = 'slug';

        $this->slug->comic = $this->comic;
        $this->comic->slug = $this->slug;

        $this->entities[] = $this->comic;
        $this->entities[] = $this->slug;

        $this->manager->persist($this->comic);
        $this->manager->persist($this->slug);
        $this->manager->flush();
    }
}