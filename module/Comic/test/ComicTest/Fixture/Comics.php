<?php

namespace ComicTest\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ComicCmsTestHelper\Fixture\FixtureRepository;
use Comic\Entity\Comic;

class Comics extends FixtureRepository
{
    protected $entityClass = 'Comic\Entity\Comic';

    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $titles = array(
            'Batman', 'Garfield', 'Superman',
            'xkcd', 'UserFriendly', 'Saturday Morning Breakfast Cereal',
        );

        foreach($titles as $title)
        {
            $entity = $this->produceComicEntity($title);
            $this->manager->persist($entity);
        }

        $this->manager->flush();
    }

    /**
    * Produces an instance of Comic object, with given title.
    *
    * @param string $title Comic title.
    * @return \Comic\Entity\Comic
    */
    public function produceComicEntity($title)
    {
        $comic = new Comic();
        $this->entities[] = $comic;
        $comic->title = $title;
        return $comic;
    }
}