<?php
/**
 * Abstract RESTful controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractRestfulController as BaseController;
use Application\Service\Authentication;
use Application\Service\Database;
use Application\Service\Dispatcher;
use Settings\Service\Settings;

class AbstractRestfulController extends BaseController
{
    use Authentication;
    use Database;
    use Settings;
    use Dispatcher;
}