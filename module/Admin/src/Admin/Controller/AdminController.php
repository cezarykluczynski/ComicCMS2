<?php

namespace Admin\Controller;

use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Application\Controller\ApplicationController;

class AdminController extends ApplicationController
{
    /**
     * @todo ACL
     */
    public function onDispatch(MvcEvent $e)
    {
        $parent = parent::onDispatch($e);

        return $parent;
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction() {
        return new ViewModel();
    }
}

