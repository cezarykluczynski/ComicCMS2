<?php
/**
 * Additional configuration for PHPUnit. This file is only included in
 * {@link \ComicCmsTestHelper\Bootstrap ComicCmsTestHelper\Boostrap.php}, and it should be kept that way.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

return array(
    'modules' => array(
        'ComicCmsTestHelper',
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array(
            '../../../config/autoload/{,*.}{global,local}.php',
        ),
    ),
);