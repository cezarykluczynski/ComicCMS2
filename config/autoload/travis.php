<?php
/**
 * Configuration for Travis, not used locally or on production.
 * This file will be moved to local.php before_script phase of travis build.
 */

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'params' => array(
                    'user'     => 'postgres',
                )
            )
        )
    ),
    'db'=> array(
        'username' => 'postgres',
    ),
);
