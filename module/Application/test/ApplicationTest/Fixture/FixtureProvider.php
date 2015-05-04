<?php

namespace ApplicationTest\Fixture;

use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

trait FixtureProvider
{
    protected $fixtures;

    public function addFixtures()
    {
        $fixtures = func_get_args();
        $loader = new Loader();

        $this->fixtures = array();

        foreach($fixtures as $fixture) {
            $fixtureClass = $this->fixtures[$fixture] = new $fixture;
            $loader->addFixture($fixtureClass);
        }

        $entityManager = $this
            ->getApplicationServiceLocator()
            ->get('doctrine.entitymanager.orm_default');

        $this->fixtures = $loader->getFixtures();

        $executor = new ORMExecutor($entityManager);
        $executor->execute($this->fixtures, true);
    }

    public function removeFixtures()
    {
        foreach($this->fixtures as $fixture)
        {
            $fixture->unload();
        }
    }
}