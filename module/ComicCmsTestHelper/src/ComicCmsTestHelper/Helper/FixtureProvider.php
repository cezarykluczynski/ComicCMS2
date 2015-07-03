<?php
/**
 * Trair for loading and unloading fixtures.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicCmsTestHelper\Helper;

use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

trait FixtureProvider
{
    /** @var array */
    protected $repositories = [];

    /** @var array */
    protected $generatedClasses = [];

    public abstract function getEntityManager();

    /**
     * Loads fixtures from a provided class.
     *
     * @param string $fixtureClassName Full name of class containing fixtures.
     * @param array  $options          Options that will be passed to class constructor.
     */
    public function loadFixtures($fixtureClassName, $options = array())
    {
        /** @var \Doctrine\Common\DataFixtures\Loader */
        $loader = new Loader();

        $this->repositories[$fixtureClassName] = isset($this->repositories[$fixtureClassName]) ?
            $this->repositories[$fixtureClassName] :
            [];

        $this->generatedClasses[$fixtureClassName] = isset($this->generatedClasses[$fixtureClassName]) ?
            $this->generatedClasses[$fixtureClassName] :
            [];

        $fixtureClass = new $fixtureClassName($options);
        $this->repositories[$fixtureClassName][] = $fixtureClass;
        $loader->addFixture($fixtureClass);

        $em = $this->getEntityManager();

        $this->generatedClasses[$fixtureClassName][] = $loader->getFixtures();

        /** @var \Doctrine\Common\DataFixtures\Executor\ORMExecutor */
        $executor = new ORMExecutor($em);
        $executor->execute([$fixtureClass], true);
    }

    /**
     * Removes previously added fixtures.
     *
     * @param string|null $fixtureClassNameFilter
     */
    public function removeFixtures($fixtureClassNameFilter = null)
    {
        foreach($this->repositories as $fixtureClassName => $fixtureList)
        {
            if ($fixtureClassNameFilter !== null && $fixtureClassName !== $fixtureClassNameFilter)
            {
                continue;
            }

            foreach($fixtureList as $fixture)
            {
                $fixture->unload();
            }

            unset($this->repositories[$fixtureClassName]);
        }
    }

    /**
     * Returns all currently loaded fixtures.
     *
     * @return array
     */
    public function getLoadedFixtures()
    {
        $fixtures = [];

        foreach($this->repositories as $fixtureClassName => $fixtureList)
        {
            foreach($fixtureList as $fixture)
            {
                /** Merge fixtures from all currently loaded classes. */
                $fixtures = array_merge($fixtures, $fixture->getLoadedFixtures());
            }
        }

        return $fixtures;
    }
}