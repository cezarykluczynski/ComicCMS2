<?php
/**
 * Abstract class for fixture classes. It provides some automatization of unloading fixtures.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicCmsTestHelper\Fixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ComicCmsTestHelper\Fixture\FixtureRepository;
use Comic\Entity\Comic;

abstract class FixtureRepository implements FixtureInterface
{
    protected $manager;
    protected $entities;
    protected $options;

    protected $entityClass = null;

    public function __construct($options = array())
    {
        $this->options = $options;
    }

    /**
    * Removes fixtures previously loaded by this repository.
    *
    * @return void
    */
    public function unload()
    {
        if ($this->entityClass === null)
        {
            throw new \Exception('self::$entityClass has to be set for FixtureInterface::unload to work.');
        }

        foreach($this->entities as $entity)
        {
            $newEntity = $this->manager
                ->find($this->entityClass, $entity->id);

            $this->manager->remove($newEntity);
        }

        $this->manager->flush();
    }
}