<?php

return array(
    'router' => array(
        'routes' => array(
            'admin-index' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Admin',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),
            'admin-signin' => array(
               'type' => 'Literal',
                'options' => array(
                    'route'    => '/admin/signin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Auth',
                        'action'        => 'signin',
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en-US',
        'translation_file_patterns' => array(
             array(
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Admin' => 'Admin\Controller\AdminController',
            'Admin\Controller\Auth' => 'Admin\Controller\AuthController'
        ),
    ),
    'module_layouts' => array(
        'Admin' => 'layout/admin',
    ),
    'view_manager' => array(
        'default_suffix'           => 'tpl',
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
        	'layout/admin'                  => __DIR__ . '/../view/layout/layout.tpl',
            'application/admin/auth/signin' => __DIR__ . '/../view/admin/auth/signin.tpl',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'create-admin' => array(
                    'options' => array(
                        'route'    => 'create-admin <email> <password>',
                        'defaults' => array(
                            'controller' => 'Admin\Controller\Auth',
                            'action'     => 'create-admin'
                        )
                    )
                )
            )
        )
    ),
);
