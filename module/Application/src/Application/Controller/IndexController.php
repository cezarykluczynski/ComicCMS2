<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Mvc\MvcEvent;

class IndexController extends ApplicationController
{
    public $user_id = null;
    public $user = null;

    public function onDispatch(MvcEvent $e)
    {
        $this->user_id = (new Container('user'))->id;

        if (!is_null($this->user_id)) {
            $this->user = $this
                ->getEntityManager()
                ->getRepository('User\Entity\User')
                ->findById($this->user_id);
        }
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }
}
