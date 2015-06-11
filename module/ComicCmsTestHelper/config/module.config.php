<?php
/**
 * ComicCmsTestHelper module config.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicCmsTestHelper;

return array(
    'controllers' => array(
        'invokables' => array(
            'ComicCmsTestHelper\Controller\CleanupConsole' => 'ComicCmsTestHelper\Controller\CleanupConsoleController',
        ),
    ),
);