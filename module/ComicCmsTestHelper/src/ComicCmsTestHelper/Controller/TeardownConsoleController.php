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
use Zend\View\Model\JsonModel;
use Zend\Json\Json;

class TeardownConsoleController extends AbstractActionController
{
    /**
     * Removes entity, based on condition.
     */
    public function removeEntityAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $response->setErrorLevel(0);

        /** Entity class name. */
        $entity = $request->getParam('entity');
        /** @var string JSON-encoded criteria. */
        $criteriaEncoded = $request->getParam('criteria');
        /** @var array Critieria for finding entities to remove. */
        $criteria = Json::decode($criteriaEncoded, Json::TYPE_ARRAY);

        $em = $this->getEntityManager();

        /** @var array Collection of entities matching criteria. */
        $entities = $em->getRepository($entity)->findBy($criteria);

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
}