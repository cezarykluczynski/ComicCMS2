<?php

namespace ComicTest\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Comic\Entity\Comic;

class MultipleEntities implements FixtureInterface
{
    protected $manager;
    protected $entities;

     /**
      * {@inheritDoc}
      */
     public function load(ObjectManager $manager)
     {
        $this->manager = $manager;

        $this->entities[] = $comic = new Comic();
        $comic->title = 'Batman';

        $this->entities[] = $comic = new Comic();
        $comic->title = 'Garfield';

        $this->entities[] = $comic = new Comic();
        $comic->title = 'Superman';

        $this->entities[] = $comic = new Comic();
        $comic->title = "xkcd";

        $this->entities[] = $comic = new Comic();
        $comic->title = "UserFriendly";

        $this->entities[] = $comic = new Comic();
        $comic->title = "Saturday Morning Breakfast Cereal";

        foreach($this->entities as $entity)
        {
            $this->manager->persist($entity);
        }

        $this->manager->flush();
     }

     public function unload()
     {
        foreach($this->entities as $entity)
        {
            $newEntity = $this->manager
                ->getRepository('Comic\Entity\Comic')
                ->findOneById($entity->id);

            $this->manager->remove($newEntity);
        }

        $this->manager->flush();
     }
 }