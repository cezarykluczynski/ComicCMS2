<?php
/**
 * Controller for user functionalities in admin panel.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace User\Controller;

use Application\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class UserRestController extends AbstractRestfulController
{
    /**
     * Paginator for user entities in admin panel.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        $view = new JsonModel;

        /** @var int */
        $limit = (int) $this->params()->fromQuery('limit', 10);
        /** @var int */
        $offset = (int) $this->params()->fromQuery('offset', 0);

        /** @var \User\Entity\User */
        $userRepository = $this->getEntityManager()
            ->getRepository('User\Entity\User');

        /** @var array */
        $users = $userRepository->paginate([
            'limit' => $limit,
            'offset' => $offset,
        ]);

        $view->setVariables([
            'users' => $users,
            'limit' => $limit,
            'count' => count($users),
            'total' => (int) $userRepository->count(),
        ]);

        return $view;
    }
}