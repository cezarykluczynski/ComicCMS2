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

        $config = $this->getServiceLocator()->get('Config');
        $widgets = $config['admin']['dashboard']['widgets'];

        $id = 0;

        foreach($widgets as &$widget)
        {
            /** Set URL to data passed to view, unset route name. */
            $widget['url'] = $url = $this->url()->fromRoute($widget['route']);
            $widget['id'] = $id++;
            unset($widget['route']);
        }

        $view->setVariable('dashboardWidgets', $widgets);

        return $view;
    }
}

