<?php
/**
 * Settings REST controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Settings\Controller;

use Application\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Settings\Entity\Setting;

class SettingsRestController extends AbstractRestfulController
{
    /**
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        /** @var \Zend\View\Model\JsonModel */
        $view = new JsonModel;
        /** @var \Doctrine\ORM\EntityManager */
        $entityManager = $this->getEntityManager();

        /** @var array */
        $settings = $entityManager->getRepository('Settings\Entity\Setting')->findAll();

        /** @var array */
        $list = array();

        foreach($settings as $setting)
        {
            $list[$setting->name] = $setting->value;
        }

        return $view->setVariable('list', $list);
    }
}