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

class ComicRestController extends AbstractRestfulController
{
    /**
     * Creates a comic entity.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function create($data)
    {
        /** @var \Zend\View\Model\JsonModel */
        $view = new JsonModel;
        /** @var \Doctrine\ORM\EntityManager */
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        /** @var \Zend\Http\PhpEnvironment\Response */
        $response = $this->getResponse();

        /** Slug has to be unique. */
        if ($entityManager
            ->getRepository('Comic\Entity\Slug')
            ->findBy([
                'slug' => $data['slug'],
            ]))
        {
            $response->setStatusCode(409);
            return $view
                ->setVariable('error', 'The given slug is already in use. Choose another slug.');
        }

        /** @var \Comic\Entity\ComicRepository */
        $comicRepository = $entityManager
            ->getRepository('Comic\Entity\Comic');

        /** Title has to be unique. */
        if ($comicRepository
            ->findBy([
                'title' => $data['title'],
            ]))
        {
            $response->setStatusCode(409);
            return $view
                ->setVariable('error', 'Comic with the given title already exists. Choose different title.');
        }

        /** @var \Comic\Entity\Comic|null */
        $comic = $comicRepository->create($data);

        $response->setStatusCode(201);
        return $view->setVariables([
            'success' => 'Comics was created.',
            'id' => $comic->id,
        ]);
    }

    /**
     * Return all the comic entities.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        /** @var \Zend\View\Model\JsonModel */
        $view = new JsonModel;
        /** @var \Doctrine\ORM\EntityManager */
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        /** @var array */
        $comics = $entityManager->getRepository('Comic\Entity\Comic')->getList();

        $view->setVariables([
            'list' => $comics,
        ]);

        return $view;
    }
}