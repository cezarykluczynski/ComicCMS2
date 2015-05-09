<?php

namespace ComicCmsTestHelper\Helper;

trait Doctrine
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $em;
    public function getEntityManager() {
        $this->em = $this
            ->getApplicationServiceLocator()
            ->get('doctrine.entitymanager.orm_default');

        return $this->em;
    }
}