<?php

namespace Comic\Controller\Admin;

use Zend\View\Model\ViewModel;
use Application\Controller\ApplicationController;

class WidgetController extends ApplicationController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        /** Initialize response template. */
        $view = new ViewModel();
        $view->setTerminal(true);

        /** Find all comics. */
        $entityManager = $this->getEntityManager();
        $comics = $entityManager
            ->getRepository('Comic\Entity\Comic')
            ->findBy(array(), null, 5);

        /** Show a introductory message if no comics are present,
         *  and a comics list, if some comics were found. */
        if (empty($comics))
        {
            $view->setTemplate('comic/admin/widget/empty');
        }
        else
        {
            $view->setTemplate('comic/admin/widget/index');
            $view->setVariable('comics', $comics);
        }

        return $view;
    }
}