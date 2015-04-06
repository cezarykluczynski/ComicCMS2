<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class IndexController extends ApplicationController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }
}
