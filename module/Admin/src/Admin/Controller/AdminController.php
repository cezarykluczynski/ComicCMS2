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
        return new ViewModel();
    }
}

