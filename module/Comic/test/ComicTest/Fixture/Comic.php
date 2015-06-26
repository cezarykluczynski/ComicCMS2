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

class Comic extends FixtureRepository
{
    protected $entityClass = 'Comic\Entity\Comic';

    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $comic = new ComicEntity();
        $this->entities[] = $comic;
        $comic->title = "Single comic title";
        $this->manager->persist($comic);
        $this->manager->flush();
    }
}