<?php
namespace Comic\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Comic\Entity\Comic;

class ComicRestController extends AbstractRestfulController
{
    public function create($data)
    {
        /** @var \Zend\View\Model\JsonModel */
        $view = new JsonModel;
        /** \Doctrine\ORM\EntityManager */
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        /** \Zend\Http\PhpEnvironment\Response */
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

        if (!($comic instanceof Comic))
        {
            $response->setStatusCode(422);
            return $view->setVariable('error', 'Comics was not created.');
        }

        return $view->setVariables([
            'success' => 'Comics was created.',

        ]);
    }
}