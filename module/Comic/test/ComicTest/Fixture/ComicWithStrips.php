<?php
/**
 * Fixtures for comic entity.
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

class ComicWithStrips extends FixtureRepository
{
    protected $entityClass = 'Comic\Entity\Strip';
    protected $comic;

    /**
     * Loads a single comic with a 10 strips.
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->comic = new ComicEntity;
        $this->comic->title = 'Comic';
        $this->entities[] = $this->comic;

        for($i = 0; $i < 10; $i++)
        {
            $strip = new Strip;
            $strip->title = "Strip title " . $i;
            $strip->comic = $this->comic;
            $this->entities[] = $strip;
            $this->manager->persist($strip);
            $this->comic->addStrip($strip);
        }

        $this->manager->persist($this->comic);
        $this->manager->flush();
    }
}