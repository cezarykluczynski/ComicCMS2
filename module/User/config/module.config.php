<?php
/**
 * User module configuration.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace User;

return array(
    'router' => array(
        'routes' => array(
            'admin-user-widget-users' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin/user/widget/users',
                    'defaults' => array(
                        '__NAMESPACE__' => 'User\Controller\Admin',
                        'controller'    => 'Widget',
                        'action'        => 'users',
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'User\Controller\Admin\Widget' => 'User\Controller\Admin\WidgetController',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'user/admin/widget/index' => __DIR__ . '/../view/user/admin/widget/index.tpl',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
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
);