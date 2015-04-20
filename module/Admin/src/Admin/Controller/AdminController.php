<?php

namespace Admin\Controller;

use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Application\Controller\ApplicationController;

class AdminController extends ApplicationController
{
    public function onDispatch(MvcEvent $e)
    {
        $parent = parent::onDispatch($e);

        if (!$this->user) {
            die("stop");
        }

        return $parent;
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction() {
        return new ViewModel();
    }
}

