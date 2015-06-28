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
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ComicCmsTestHelper\Helper\Converter;
use ComicCmsTestHelper\Helper\FixtureProvider;
use ComicCmsTestHelper\Helper\FQN;

class SetupConsoleController extends AbstractActionController
{
    use Converter;
    use FixtureProvider;
    use FQN;

    /**
     * Removes entity, based on condition.
     */
    public function createEntityAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $response->setErrorLevel(0);

        /** Entity FQN. */
        $className = $this->getFQN($request->getParam('entityName'));
        /** @var array Data to hydrate entity with. */
        $data = $this->getJSONStringAsArray($request->getParam('data'));

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

    /**
     * Loads given fixture class and returns an object later passed to
     * {@link \ComicCmcTextHelper\Controller\TeardownConsoleController::unloadFixturesAction}.
     */
    public function loadFixturesAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $response->setErrorLevel(0);

        /** Fixtures FQN. */
        $className = $this->getFQN($request->getParam('className'));

        /** Load requested fixtures. */
        $this->loadFixtures($className);

        /** @var array All loaded fixtures. */
        $fixtures = $this->getLoadedFixtures();

        /** @var array JSON response. */
        $response = [];

        /**
         * Go over all fixtures and create a key-value array, where keys are class names,
         * with dots in place of backslashes, and values are arrays of entity ID's.
         */
        foreach($fixtures as $fixture)
        {
            $fixtureClassName = str_replace('\\', '.', get_class($fixture));

            if (!isset($response[$fixtureClassName]))
            {
                $response[$fixtureClassName] = [];
            }

            $response[$fixtureClassName][] = $fixture->id;
        }

        return JSON::encode($response);
    }
}