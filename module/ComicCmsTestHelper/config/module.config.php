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
            'ComicCmsTestHelper\Controller\TeardownConsole' => 'ComicCmsTestHelper\Controller\TeardownConsoleController',
        ),
    ),

     'console' => array(
        'router' => array(
            'routes' => array(
                'remove-entity' => array(
                    'options' => array(
                        'route'    => 'remove-entity <entity> <criteria>',
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