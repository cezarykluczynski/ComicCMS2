<?php

namespace User\Controller\Admin;

use Zend\View\Model\ViewModel;
use Application\Controller\ApplicationController;

class WidgetController extends ApplicationController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        /** Initialize response template without layout. */
        $view = new ViewModel();
        $view->setTerminal(true);

        return $view;
    }
}