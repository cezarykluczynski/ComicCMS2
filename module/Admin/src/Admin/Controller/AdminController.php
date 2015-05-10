<?php

namespace Admin\Controller;

use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Application\Controller\ApplicationController;

class AdminController extends ApplicationController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction() {
        $view = new ViewModel();

        /** @var array */
        $widgets = $this->getServiceLocator()->get('Config')['admin']['dashboard']['widgets'];

        /** @var integer */
        $id = 0;

        foreach($widgets as &$widget)
        {
            /** Set URL to data passed to view, unset route name. */
            $widget['url'] = $this->url()->fromRoute($widget['route']);
            $widget['id'] = $id++;
            unset($widget['route']);
        }

        $view->setVariables([
            'dashboardWidgets' => $widgets,
            'authenticatedUser' => $this->authenticatedUser,
        ]);

        return $view;
    }
}

