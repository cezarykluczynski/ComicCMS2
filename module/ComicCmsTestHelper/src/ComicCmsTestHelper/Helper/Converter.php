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

trait Converter
{
    /**
     * Returns JSON response as array.
     *
     * @return array JSON response as array.
     */
    public function getJSONResponseAsArray() {
        return Json::decode($this->getResponse()->getContent(), Json::TYPE_ARRAY);
    }
}