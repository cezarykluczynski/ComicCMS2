<?php

// For PHP <= 5.4, you should replace any ::class references with strings
// remove the first \ and the ::class part and encase in single quotes

return [
    'db'=> array(
        'driver' => 'pgsql',
        'username' => getenv('TRAVIS') ? 'postgres' : 'comiccms',
        'database' => 'comiccms',
        'password' => '',
        'host' => 'localhost',
        'port'=> '5432',
    ),
    'bjyauthorize' => [

        // set the 'guest' role as default (must be defined in a role provider)
        'default_role' => 'guest',

        /* this module uses a meta-role that inherits from any roles that should
         * be applied to the active user. the identity provider tells us which
         * roles the "identity role" should inherit from.
         * for ZfcUser, this will be your default identity provider
        */
        // 'identity_provider' => 'BjyAuthorize\Provider\Identity\ZfcUserZendDb',
        'identity_provider' => 'User\Provider\Identity\UserIdentityProvider',

        /* role providers simply provide a list of roles that should be inserted
         * into the Zend\Acl instance. the module comes with two providers, one
         * to specify roles in a config file and one to load roles using a
         * Zend\Db adapter.
         */
        'role_providers' => [
            // this will load roles from the user_role table in a database
            // format: user_role(role_id(varchar], parent(varchar))
            'BjyAuthorize\Provider\Role\ZendDb' => [
                'table'                 => 'roles',
                'identifier_field_name' => 'role_id',
                'role_id_field'         => 'role_id',
                'parent_role_field'     => 'parent_id',

            ],
        ],

        // resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
            ],
        ],

        /* rules can be specified here with the format:
         * [roles (array], resource, [privilege (array|string], assertion])
         * assertions will be loaded using the service manager and must implement
         * Zend\Acl\Assertion\AssertionInterface.
         * *if you use assertions, define them using the service manager!*
         */
        'rule_providers' => [
            'BjyAuthorize\Provider\Rule\Config' => [
                'allow' => [
                ],
                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny' => [
                ],
            ],
        ],

        'guards' => [
            'BjyAuthorize\Guard\Controller' => [
                ['controller' => 'Application\Controller\Index', 'action' => 'index', 'roles' => ['guest']],
                ['controller' => 'Admin\Controller\Auth', 'action' => 'signin', 'roles' => ['guest']],
                ['controller' => 'Admin\Controller\Admin', 'action' => 'index', 'roles' => ['admin']],

                /** Techically, console routing does not require ACL.
                 *  Anyone with access to CLI is authenticated enough, or cannot be stopped anyway. */
                ['controller' => 'Admin\Controller\Auth', 'action' => 'create-admin', 'roles' => ['guest']],
            ],
        ],
    ],
];