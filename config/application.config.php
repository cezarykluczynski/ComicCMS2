<?php
/**
 * Main application configuration.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

return array(
    'modules' => array(
        'EdpModuleLayouts',
        'BjyAuthorize',
        'DoctrineModule',
        'DoctrineORMModule',
        'Settings',
        'Application',
        'Admin',
        'Asset',
        'User',
        'Comic',
        'SmartyModule',
        'ComicCmsTestHelper',
    ),

    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),

        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
    ),
);
