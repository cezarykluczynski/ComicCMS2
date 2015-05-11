<?php
/**
 * Doctrine helper. Use when needed. It's quite RAM depleting.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicCmsTestHelper\Helper;

trait Doctrine
{
    /** @var \Doctrine\ORM\EntityManager */
    protected $em;

    /**
     * @return \Doctrine\Orm\EntityManager
     */
    public function getEntityManager() {
        $this->em = $this
            ->getApplicationServiceLocator()
            ->get('doctrine.entitymanager.orm_default');

        return $this->em;
    }
}