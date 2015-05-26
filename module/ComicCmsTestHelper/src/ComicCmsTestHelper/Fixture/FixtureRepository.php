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
        foreach($this->entities as $key => $entity)
        {
            $newEntity = $this->manager
                ->find(get_class($entity), $entity->id);

            if (is_null($newEntity))
            {
                continue;
            }

            $this->manager->remove($newEntity);
            unset($this->entities[$key]);
        }

        $this->manager->flush();
    }

    public function getLoadedFixtures()
    {
        return (array) $this->entities;
    }
}