<?php

namespace ComicCmsTestHelper\Helper;

use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

trait FixtureProvider
{
    protected $fixtures = array();

    public function loadFixtures($fixtureClassName, $options = array())
    {
        $loader = new Loader();

        $fixtureClass = $this->fixtures[$fixtureClassName] = new $fixtureClassName($options);
        $loader->addFixture($fixtureClass);

        $this->getEntityManager();

        $this->fixtures = $loader->getFixtures();

        $executor = new ORMExecutor($this->em);
        $executor->execute($this->fixtures, true);
    }

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