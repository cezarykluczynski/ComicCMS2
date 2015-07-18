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
        /** @var \Zend\Di\ServiceLocator */
        $sl = $this->getServiceLocator();

        /** @var array */
        $settingsCollection = $entityManager->getRepository('Settings\Entity\Setting')->findAll();

        /** @var array */
        $settings = $sl->get('Settings');

        /** @var \Settings\Service\ManifestService Service for rediscovering new plugins and templates. */
        $extensionManifest = $sl->get('Settings\ExtensionManifest');

        /** Inject current settings. */
        $extensionManifest->setSettingsCollection($settingsCollection);

        /** Inject settings tree. */
        $extensionManifest->setSettings($settings);
        $extensionManifest->discover();

        $parsingErrors = $extensionManifest->getParsingErrors();

        /** @var array */
        $list = array();

        foreach($settingsCollection as $setting)
        {
            $list[$setting->name] = $setting->value;
        }

        return $view->setVariables([
            'list' => $list,
            'parsingErrors' => $parsingErrors,
        ]);
    }
}