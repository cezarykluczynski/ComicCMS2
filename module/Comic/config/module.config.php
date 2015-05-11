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
            'admin-comic-widget-index' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin/comic/widget/index',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Comic\Controller\Admin',
                        'controller'    => 'Widget',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),
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
            'comic/admin/widget/index' => __DIR__ . '/../view/comic/admin/widget/index.tpl',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Comic\Controller\Admin\Widget' => 'Comic\Controller\Admin\WidgetController',
        ),
    ),
);