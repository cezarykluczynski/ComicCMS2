<?php

return array(
    'settings_groups' => array(
        array(
            'name' => 'General settings',
            'prefix' => 'general:',
            'settings' => array(
                array(
                    'name' => 'default_template',
                    'label' => 'Default template',
                    'default_value' => 'default',
                ),
                array(
                    'name' => 'development_mode',
                    'label' => 'Development mode',
                    'default_value' => '1',
                ),
            ),
        ),
    ),
);