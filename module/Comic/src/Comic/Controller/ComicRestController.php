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
use Comic\Entity\Comic;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

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
        $entityManager = $this->getEntityManager();
        /** @var \Zend\Http\PhpEnvironment\Response */
        $response = $this->getResponse();

        $slug = $entityManager
            ->getRepository('Comic\Entity\Slug')
            ->findOneBy([
                'slug' => $data['slug'],
            ]);

        /** Slug has to be unique. */
        if ($slug && $slug->comic)
        {
            $response->setStatusCode(409);
            return $view->setVariables([
                'error' => sprintf(
                    'Comic "%s" already uses slug "%s". Pick another slug.',
                    $slug->comic->title,
                    $slug->slug
                ),
            ]);
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
                ->setVariable(
                    'error',
                    sprintf(
                        'Comic with the title "%s" already exists. Choose different title.',
                        $data['title']
                    )
                );
        }

        /** If free slug was found, it should be used when creating comic entity. */
        if ($slug)
        {
            $data['slugEntity'] = $slug;
        }

        /** @var \Comic\Entity\Comic|null */
        $comic = $comicRepository->create($data);

        $response->setStatusCode(201);
        return $view->setVariables([
            'success' => 'Comic was created.',
            'id' => $comic->id,
        ]);
    }

    /**
     * Updates a comic entity.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function update($id, $data)
    {
        /** @var \Zend\View\Model\JsonModel */
        $view = new JsonModel;
        /** @var \Doctrine\ORM\EntityManager */
        $entityManager = $this->getEntityManager();
        /** @var \Zend\Http\PhpEnvironment\Response */
        $response = $this->getResponse();

        /** \Comic\Entity\Comic|null */
        $comic = $entityManager->find('Comic\Entity\Comic', (int) $id);

        /** Comic has to exist to be updated. */
        if (is_null($comic))
        {
            $response->setStatusCode(404);
            return $view
                ->setVariable('error', 'Comic cannot be updated, because it does not exists.');
        }

        /** @var \Comic\Entity\Slug|null */
        $slug = $entityManager->getRepository('Comic\Entity\Slug')->findOneBy([
            'slug' => $data['slug']['slug'],
        ]);

        /** If slug was found on another comics, data cannot be saved. */
        if ($slug && $slug->comic && $slug->comic->id != $id)
        {
            $response->setStatusCode(409);
            return $view->setVariables([
                'error' => sprintf(
                    'Comic "%s" already uses slug "%s". Pick another slug.',
                    $slug->comic->title,
                    $slug->slug
                ),
            ]);
        }

        $comic->slug->slug = $data['slug']['slug'];
        /** It's important to remove slug from data now, so comic hydrator won't create new slug entity. */
        unset($data['slug']);

        /** \DoctrineModule\Stdlib\Hydrator\DoctrineObject */
        $hydrator = new DoctrineHydrator($entityManager, false);

        /** Update entity with post data, then save it. */
        $hydrator->hydrate($data, $comic);
        $entityManager->persist($comic);
        $entityManager->flush();

        return $view->setVariables([
            'success' => 'Comic was updated.',
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
        $entityManager = $this->getEntityManager();
        /** @var array */
        $comics = $entityManager->getRepository('Comic\Entity\Comic')->getList();

        $view->setVariables([
            'list' => $comics,
        ]);

        return $view;
    }

    /**
     * Delete entity.
     */
    public function delete($id)
    {
        /** @var \Zend\View\Model\JsonModel */
        $view = new JsonModel;
        /** @var \Doctrine\ORM\EntityManager */
        $entityManager = $this->getEntityManager();
                /** @var \Zend\Http\PhpEnvironment\Response */
        $response = $this->getResponse();

        /** \Comic\Entity\Comic|null */
        $comic = $entityManager->find('Comic\Entity\Comic', (int) $id);

        /** Comic has to exist to be updated. */
        if (is_null($comic))
        {
            $response->setStatusCode(404);
            return $view
                ->setVariable('error', 'Comic cannot be deleted, because it does not exists.');
        }

        /** Remove comic. */
        $entityManager->remove($comic);
        $entityManager->flush();

        return $view->setVariable('success', sprintf('Comic "%s" was deleted.', $comic->title));
    }
}