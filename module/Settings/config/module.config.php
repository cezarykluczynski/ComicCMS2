<?php
/**
 * Settings module configuration.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Settings;

return array(
    'router' => array(
        'routes' => array(
            'rest-settings' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route' => '/rest/settings[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Settings\Controller',
                        'controller' => 'SettingsRest',
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Settings\Controller\SettingsRest' => 'Settings\Controller\SettingsRestController',
        ),
    ),
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'comic-rest' => __DIR__ . '/../view',
        ),
        'angular_templates' => array(
            'admin' => array(
                __DIR__ . '/../view/settings/template',
            ),
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);