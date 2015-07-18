<?php
/**
 * Dispatcher trait for other traits initialization.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Application\Service;

use Zend\Mvc\MvcEvent;

trait Dispatcher
{
    public function onDispatch(MvcEvent $e)
    {
        $this->onDispatchAuthentication($e);
        $this->onDispatchSettings($e);

        return parent::onDispatch($e);
    }
}