<?php

/**
 * Asset module configuration.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Asset;

return array(
    'router' => array(
        'routes' => array(
            'rest-image' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/rest/image[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Asset\Controller',
                        'controller'    => 'ImageRest',
                    ),
                ),
                'may_terminate' => true,
            ),
            'rest-upload' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/rest/upload[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Asset\Controller',
                        'controller'    => 'UploadRest',
                    ),
                ),
                'may_terminate' => true,
            ),

        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'Asset\UploadCdn' => 'Asset\Service\UploadCdn',
            'Asset\UploadValidator' => 'Asset\Validator\UploadValidator',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Asset\Controller\ImageRest' => 'Asset\Controller\ImageRestController',
            'Asset\Controller\UploadRest' => 'Asset\Controller\UploadRestController',
        ),
    ),
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
                ),
            ),
        )
    ),
     'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'validators' => array(
        'Asset\Validator\UploadValidator' => array(
            'maximumSize' => 1024 * 1024 * 10,
            /**
             * To avoid type juggling back and forth in validator itfels,
             * it seems like the best way to validate against array is to pass it first as an array,
             *  and then as a string, so hello, PHP Alternative Hungarian Notation.
             */
            'asAllowedTypes' => [
                'image/jpeg',
                'image/png',
                'image/gif',
            ],
            'sAllowedTypes' => 'image/jpeg, image/png, image/gif',
            'messageVariables' => [
                'maximumSize' => 'maximumSize',
                'allowedTypes' => 'sAllowedTypes',
            ],
        ),
    ),
);