<?php
/**
 * Travis configuration. This file is copied by travis travis.local.php.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

return array(
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                /** Doctrune Proxy classes are turned off on dev, but Travis apparently require them. */
                'generate_proxies' => true,
            ),
        ),
    ),
);
