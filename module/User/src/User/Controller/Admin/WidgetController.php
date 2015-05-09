<?php

namespace User\Controller\Admin;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Controller\ApplicationController;

class WidgetController extends ApplicationController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        /** Initialize response template without layout. */
        $view = new ViewModel;
        $view->setTerminal(true);

        return $view;
    }

    /**
     * Paginator for user entities in admin panel.
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function usersAction()
    {
        $view = new JsonModel;

        $limit = (int) $this->params()->fromQuery('limit', 10);
        $page = (int) $this->params()->fromQuery('page', 1);

        /** @var \User\Entity\User */
        $userRepository = $this->getEntityManager()
            ->getRepository('User\Entity\User');

        /** @var array */
        $users = $userRepository->paginate([
            'limit' => $limit,
            'offset' => ($page - 1) * $limit,
        ]);

        $view->setVariables([
            'users' => $users,
            'limit' => $limit,
            'count' => count($users),
            'total' => $userRepository->count(),
        ]);

        return $view;
    }
}