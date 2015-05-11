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
    protected $fixtures = array();

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

        $fixtureClass = $this->fixtures[$fixtureClassName] = new $fixtureClassName($options);
        $loader->addFixture($fixtureClass);

        $this->getEntityManager();

        $this->fixtures = $loader->getFixtures();

        /** @var \Doctrine\Common\DataFixtures\Executor\ORMExecutor */
        $executor = new ORMExecutor($this->em);
        $executor->execute($this->fixtures, true);
    }

    /**
     * Removes previously added fixtures.
     *
     * @param string|null $fixtureClassNameFilter
     */
    public function removeFixtures($fixtureClassNameFilter = null)
    {
        foreach($this->fixtures as $fixtureClassName => $fixture)
        {
            if ($fixtureClassNameFilter && $fixtureClassName !== $fixtureClassNameFilter)
            {
                continue;
            }

            $fixture->unload();
            unset($this->fixtures[$fixtureClassName]);
        }
    }
}