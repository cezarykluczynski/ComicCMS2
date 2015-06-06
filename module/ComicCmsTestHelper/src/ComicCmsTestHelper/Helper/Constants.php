<?php
/**
 * Helper for converting data to a convenient format.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicCmsTestHelper\Helper;

use Zend\Json\Json;

trait Constants
{
    /**
     * Returns highest possible integer.
     *
     * @return array Highest possible integer.
     */
    public function getHighestInteger()
    {
        return 2147483647;
    }
}
