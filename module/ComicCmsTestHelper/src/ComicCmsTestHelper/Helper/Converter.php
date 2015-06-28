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
        return $this->getJSONStringAsArray($this->getResponseContent());
    }

    /**
     * Returns JSON string as array.
     *
     * @return array
     */
    public function getJSONStringAsArray($string)
    {
        return Json::decode($string, Json::TYPE_ARRAY);
    }
}