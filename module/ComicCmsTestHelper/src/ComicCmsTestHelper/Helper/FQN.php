<?php
/**
 * Helper for converting class names pased from node.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicCmsTestHelper\Helper;

trait FQN
{
    /**
     * Converts class name with dots into FQN.
     *
     * @param string $param Class name, for example "Comic.Entity.Comic".
     * @return string       FQN, for example "\Comic\Entity\Comic";
     */
    protected function getFQN($param)
    {
        /** Entity class name. */
        $className = str_replace('.', '\\', $param);
        /** Add forward bashslash, if not present. */
        $className = $className[0] === '\\' ? $className : '\\' . $className;

        return $className;
    }
}