<?php
/**
 * Comic REST controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Comic\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Comic\Entity\Comic;

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
        $comicId = $this->params()->fromRoute('comicId');

        /** @var \Zend\View\Model\JsonModel */
        $view = new JsonModel;
        /** @var \Doctrine\ORM\EntityManager */
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        /** @var array */
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

        $view->setVariables([
            'list' => $comic->strips,
        ]);

        return $view;
    }
}