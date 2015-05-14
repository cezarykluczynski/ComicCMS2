<?php
/**
 * Admin dashboard controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Application\Controller\ApplicationController;

class AdminController extends ApplicationController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction() {
        $view = new ViewModel();

        /** @var \Zend\Di\ServiceLocator */
        $this->sl = $this->getServiceLocator();

        /** @var array */
        $this->config = $this->sl->get('Config');

        /** @var \Doctrine\ORM\EntityManager */
        $this->em = $this->getEntityManager();

        /** @var array */
        $comics = $this->_getComics();
        /** @var array */
        $widgets = $this->_getWidgets();

        $view->setVariables([
            'dashboardWidgets' => $widgets,
            'authenticatedUser' => $this->authenticatedUser,
            'comics' => $comics,
        ]);

        return $view;
    }

    /**
     * Gets first five comics.
     *
     * @return array Array of comic entities.
     */
    protected function _getComics()
    {
        return $this->em
            ->getRepository('Comic\Entity\Comic')
            ->findBy(array(), null, 5);
    }

    /**
     * Load all dashboard widgets.
     *
     * @return array All dashboard widgets, ready for view.
     */
    protected function _getWidgets()
    {
        /** @var array */
        $widgets = $this->config['admin']['dashboard']['widgets'];

        /** @var integer */
        $id = 0;

        foreach($widgets as &$widget)
        {
            $widget['id'] = $id++;
        }

        return $widgets;
    }
}

