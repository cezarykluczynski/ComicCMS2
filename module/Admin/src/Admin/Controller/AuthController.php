<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;

class AuthController extends AdminController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function signinAction()
    {
        return new ViewModel();
    }
}
