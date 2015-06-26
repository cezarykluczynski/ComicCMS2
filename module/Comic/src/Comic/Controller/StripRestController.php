<?php
/**
 * Comic REST controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Comic\Controller;

use Application\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Comic\Entity\Strip;

class StripRestController extends AbstractRestfulController
{
    /**
     * Return all the comic entities.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        /** @var string Comic ID. */
        $comicId = (int) $this->params()->fromRoute('comicId');
        /** @var \Zend\View\Model\JsonModel */
        $view = new JsonModel;
        /** @var \Doctrine\ORM\EntityManager */
        $entityManager = $this->getEntityManager();
        /** @var \Comic\Entity\Comic|null */
        $comic = $entityManager->find('Comic\Entity\Comic', $comicId);

        if (!$comic)
        {
            /** If no comics was found, list is empty. */
            $this->getResponse()->setStatusCode(404);
            return $view->setVariables([
                'list' => array(),
                'error' => true,
            ]);
        }

        $strips = $entityManager->getRepository('Comic\Entity\Strip')->findBy([
            'comic' => $comic,
        ]);

        $cdn = $this->serviceLocator->get('Asset\UploadCdn');

        $response = [];

        foreach($strips as $strip)
        {
            $response[] = [
                'id' => $strip->id,
                'title' => $strip->title,
                'cover' => $cdn->canonicalRelativePathToUri($strip->getFirstImageCanonicalRelativePath()),
            ];
        }

        $view->setVariables([
            'list' => $response,
        ]);

        return $view;
    }

    /**
     * Create strip entity.
     */
    public function create($data)
    {
        /** @var string Comic ID. */
        $comicId = (int) $this->params()->fromRoute('comicId');
        /** @var \Zend\View\Model\JsonModel */
        $view = new JsonModel;
        /** @var \Doctrine\ORM\EntityManager */
        $entityManager = $this->getEntityManager();
        /** @var \Comic\Entity\Comic|null */
        $comic = $entityManager->find('Comic\Entity\Comic', $comicId);

        if (!$comic)
        {
            /** If no comics was found, strip cannot be saved. */
            $this->getResponse()->setStatusCode(404);
            return $view->setVariables([
                'error' => 'Strip cannot be create for non-existing comic.',
            ]);
        }

        /** @var \Comic\Entity\Strip Newly created strip. */
        $strip = $entityManager->getRepository('Comic\Entity\Strip')->createFromPost($data, $comic);

        return $view->setVariables([
            'success' => true,
            'stripId' => $strip->id,
        ]);
    }
}