<?php

return array(
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                /** On the other hand, Travis require Doctrine proxy classes. */
                'generate_proxies' => true,
            ),
        ),
    ),
);
