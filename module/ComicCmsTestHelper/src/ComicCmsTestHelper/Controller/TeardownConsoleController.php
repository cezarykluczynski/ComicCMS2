<?php
/**
 * Console helper for cleaning up after tests are executed.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicCmsTestHelper\Controller;

use Application\Controller\AbstractActionController;
use Zend\Json\Json;
use ComicCmsTestHelper\Helper\Converter;
use ComicCmsTestHelper\Helper\FixtureProvider;
use ComicCmsTestHelper\Helper\FQN;

class TeardownConsoleController extends AbstractActionController
{
    use Converter;
    use FixtureProvider;
    use FQN;

    /**
     * Removes entity, based on condition.
     */
    public function removeEntityAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $response->setErrorLevel(0);

        /** Entity FQN. */
        $className = $this->getFQN($request->getParam('entityName'));
        /** @var array Critieria for finding entities to remove. */
        $criteria =  $this->getJSONStringAsArray($request->getParam('criteria'));

        $em = $this->getEntityManager();

        /** @var array Collection of entities matching criteria. */
        $entities = $em->getRepository($className)->findBy($criteria);

        /** Nothing to remove, return. */
        if (empty($entities))
        {
            return 0;
        }

        /** Remove entities. */
        foreach($entities as $entity)
        {
            $em->remove($entity);
        }
        $em->flush();

        return 0;
    }

    /**
     * Remove entities, based of response from
     * {@link \ComicCmcTextHelper\Controller\SetupConsoleController::loadFixturesAction}.
     */
    public function unloadFixturesAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $response->setErrorLevel(0);

        /** @var array Entity class names and ID's. */
        $entitiesIds = $this->getJSONStringAsArray($request->getParam('entitiesIds'));

        $em = $this->getEntityManager();

        /** Remove all given entities. */
        foreach($entitiesIds as $className => $ids)
        {
            $className = $this->getFQN($className);

            foreach($ids as $id)
            {
                $entity = $em->find($className, $id);
                $em->remove($entity);
            }
        }

        $em->flush();

        return 0;
    }
}