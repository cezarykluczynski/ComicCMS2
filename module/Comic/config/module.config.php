<?php
/**
 * Comic module configuration.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Comic;

return array(
    'router' => array(
        'routes' => array(
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
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'template_map' => array(
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'angular_templates' => array(
            'admin' => array(
                __DIR__ . '/../view/comic/template',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Comic\Controller\Admin\Widget' => 'Comic\Controller\Admin\WidgetController',
        ),
    ),
);