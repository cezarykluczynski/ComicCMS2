<?php
/**
 * Application module main class.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Json\Json;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /**
         * Handler for pretty printing JSON responses. Taken from
         * {@link https://juriansluiman.nl/article/140/pretty-print-json-in-your-rest-api this blog post}.
         */
        $eventManager->attach(MvcEvent::EVENT_FINISH, function($e) {
            $response = $e->getResponse();

            /**
             * Don't pretty print JSON unti this is resolved:
             * @link https://github.com/zendframework/zend-json/issues/2
             */
            return;

            if (!method_exists($response, 'getHeaders'))
            {
                return;
            }

            $headers  = $response->getHeaders();
            if (!$headers->has('Content-Type'))
            {
                return;
            }

            $contentType = $headers->get('Content-Type');
            $value  = $contentType->getFieldValue();
            if (false !== strpos('application/json', $value)) {
                return;
            }

            $body = $response->getContent();
            $body = Json::prettyPrint($body, array(
                'indent' => '  '
            ));
            $body = $body . "\n";
            $response->setContent($body);
        });


    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
