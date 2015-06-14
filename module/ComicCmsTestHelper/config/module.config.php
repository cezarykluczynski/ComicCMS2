<?php
/**
 * ComicCmsTestHelper module config.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicCmsTestHelper;

return array(
    'controllers' => array(
        'invokables' => array(
            'ComicCmsTestHelper\Controller\SetupConsole' => 'ComicCmsTestHelper\Controller\SetupConsoleController',
            'ComicCmsTestHelper\Controller\TeardownConsole' => 'ComicCmsTestHelper\Controller\TeardownConsoleController',
        ),
    ),

     'console' => array(
        'router' => array(
            'routes' => array(
                'create-entity' => array(
                    'options' => array(
                        'route'    => 'create-entity <entityName> <data>',
                        'defaults' => array(
                            'controller' => 'ComicCmsTestHelper\Controller\SetupConsole',
                            'action'     => 'create-entity'
                        ),
                    ),
                ),
                'remove-entity' => array(
                    'options' => array(
                        'route'    => 'remove-entity <entityName> <criteria>',
                        'defaults' => array(
                            'controller' => 'ComicCmsTestHelper\Controller\TeardownConsole',
                            'action'     => 'remove-entity'
                        ),
                    ),
                ),
            ),
        ),
    ),
);