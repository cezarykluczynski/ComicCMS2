<?php
/**
 * Global Configuration Override.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '5432',
                    'dbname'   => 'comiccms',
                    'user'     => getenv('TRAVIS_POSTGRES_USER') ?: 'comiccms',
                    'password' => '',
                )
            )
        ),
        'configuration' => array(
            'orm_default' => array(
                /** Don't generate proxy classes: otherwise some rename bugs would randomly happen. */
                'generate_proxies' => false,
            ),
        ),
    ),
);
