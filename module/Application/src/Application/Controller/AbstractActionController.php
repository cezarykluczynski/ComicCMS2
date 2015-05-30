<?php
/**
 * Abstract action controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController as BaseController;
use Application\Service\Authentication;
use Application\Service\Database;

class AbstractActionController extends BaseController
{
    use Authentication;
    use Database;
}