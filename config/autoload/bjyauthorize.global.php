<?php
/**
 * ACL config. More information available {@link https://github.com/bjyoungblood/BjyAuthorize here}.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

return [
    'db'=> array(
        'driver' => 'pgsql',
        'username' => getenv('TRAVIS_POSTGRES_USER') ?: 'comiccms',
        'database' => 'comiccms',
        'password' => '',
        'host' => 'localhost',
        'port'=> '5432',
    ),
    'bjyauthorize' => [
        'default_role' => 'guest',
        'identity_provider' => getenv('BJYAUTHORIZE_IDENTITY_PROVIDER') ?: 'User\Provider\Identity\UserIdentityProvider',
        'role_providers' => [
            'BjyAuthorize\Provider\Role\ZendDb' => [
                'table'                 => 'roles',
                'identifier_field_name' => 'role_id',
                'role_id_field'         => 'role_id',
                'parent_role_field'     => 'parent_id',

            ],
        ],

        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
            ],
        ],

        'rule_providers' => [
            'BjyAuthorize\Provider\Rule\Config' => [
                'allow' => [
                ],
                'deny' => [
                ],
            ],
        ],

        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                ['controller' => 'Application\Controller\Index', 'action' => 'index', 'roles' => ['guest']],
                ['controller' => 'Admin\Controller\Auth', 'action' => 'signin', 'roles' => ['guest']],
                ['controller' => 'Admin\Controller\Auth', 'action' => 'signout', 'roles' => ['guest']],

                /** Admin panel. */
                ['controller' => 'Admin\Controller\Admin', 'action' => 'index', 'roles' => ['admin']],

                /** Users RESTful controller. */
                ['controller' => 'User\Controller\UserRest', 'action' => null, 'roles' => ['admin']],

                /** Comics RESTful controllers. */
                ['controller' => 'Comic\Controller\ComicRest', 'action' => null, 'roles' => ['admin']],
                ['controller' => 'Comic\Controller\StripRest', 'action' => null, 'roles' => ['admin']],

                /** Techically, console routing does not require ACL.
                 *  Anyone with access to CLI is authenticated enough, or cannot be stopped anyway. */
                ['controller' => 'Admin\Controller\Auth', 'action' => 'create-admin', 'roles' => ['guest']],
                ['controller' => 'Admin\Controller\Auth', 'action' => 'get-admin-session-id', 'roles' => ['guest']],
            ],
        ],
    ],
];