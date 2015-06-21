<?php
/**
 * Image listener.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Asset\Entity;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class ImageListener
{
    /**
     * If image entity is removed, corresponding file on disk has to be removed too.
     */
    public function preRemove(Image $entity, LifecycleEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $repository = $em->getRepository('Asset\Entity\Image');
        $repository->removeEntityImage($entity);
    }
}