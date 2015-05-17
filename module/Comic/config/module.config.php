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
            'rest-comic' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/rest/comic[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Comic\Controller',
                        'controller'    => 'ComicRest',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Comic\Controller\ComicRest' => 'Comic\Controller\ComicRestController',
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
            'comic-rest' => __DIR__ . '/../view',
        ),
        'angular_templates' => array(
            'admin' => array(
                __DIR__ . '/../view/comic/template',
            ),
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);