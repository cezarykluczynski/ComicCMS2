<?php
/**
 * Correct fixture manifest.
 */
return array(
    'settings_groups' => array(
        array(
            'name' => 'Settings group name',
            'prefix' => 'group:',
            'settings' => array(
                array(
                    'name' => 'first_name',
                    'label' => 'First name',
                    'default_value' => 'name',
                ),
                array(
                    'name' => 'second_name',
                    'label' => 'Second name',
                ),
            ),
        ),
    ),
);