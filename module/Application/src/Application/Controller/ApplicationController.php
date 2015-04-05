<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class ApplicationController extends AbstractActionController
{
    /**
     * @var Object
     */
    public $adapter;

    /**
     * Returns DB adapter.
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getDbAdapter()
    {
        if (!$this->adapter) {
            $this->adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        }
        return $this->adapter;
    }
}