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

        $titles = array(
            'Batman', 'Garfield', 'Superman',
            'xkcd', 'UserFriendly', 'Saturday Morning Breakfast Cereal',
        );

        foreach($titles as $title)
        {
            $entity = $this->produceComicInstance($title);
            $this->manager->persist($entity);
        }

        $this->manager->flush();
     }

     /**
      * Removes fixtures previously loaded by this fixture.
      * @return void
      */
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

     /**
      * Produces an instance of Comic object, with given title.
      * @param string $title Comic title.
      * @return \Comic\Entity\Comic
      */
     public function produceComicInstance($title)
     {
         $comic = new Comic();
         $this->entities[] = $comic;
         $comic->title = $title;
         return $comic;
     }
 }