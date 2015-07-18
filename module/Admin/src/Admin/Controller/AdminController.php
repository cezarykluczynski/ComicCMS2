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
use Application\Controller\AbstractActionController;

class AdminController extends AbstractActionController
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

        /** @var array */
        $widgets = $this->getWidgets();

        $view->setVariables([
            'dashboardWidgets' => $widgets,
            'authenticatedUser' => $this->authenticatedUser,
        ]);

        return $view;
    }

    /**
     * Load all dashboard widgets.
     *
     * @return array All dashboard widgets, ready for view.
     */
    protected function getWidgets()
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

