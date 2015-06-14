<?php
/**
 * Console helper for creating entities for tests.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicCmsTestHelper\Controller;

use Application\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class SetupConsoleController extends AbstractActionController
{
    /**
     * Removes entity, based on condition.
     */
    public function createEntityAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $response->setErrorLevel(0);

        /** Entity class name, with dots in place of backslashes. */
        $entityName = $request->getParam('entityName');
        /** Entity class name. */
        $className = str_replace('.', '\\', $entityName);
        /** Add forward bashslash, if not present. */
        $className  = $className[0] === '\\' ? $className : '\\' . $className;
        /** @var string JSON-encoded data. */
        $dataEncoded = $request->getParam('data');
        /** @var array Data to hydrate entity with. */
        $data = Json::decode($dataEncoded, Json::TYPE_ARRAY);

        $em = $this->getEntityManager();

        $entity = new $className;

        /** \DoctrineModule\Stdlib\Hydrator\DoctrineObject */
        $hydrator = new DoctrineHydrator($em, false);

        /** Update entity with post data, then save it. */
        $hydrator->hydrate($data, $entity);
        $em->persist($entity);
        $em->flush();

        return 0;
    }
}