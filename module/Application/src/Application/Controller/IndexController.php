<?php
/**
 * Main page controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }
}
