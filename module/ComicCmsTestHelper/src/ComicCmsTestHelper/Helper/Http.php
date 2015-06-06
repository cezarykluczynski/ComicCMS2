<?php
/**
 * Helper for request and response.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicCmsTestHelper\Helper;

use Zend\Http\Headers;
use Zend\Json\Json;

trait Http
{
    /**
     * Sets request headers to json.
     *
     * @return void.
     */
    public function setJSONRequestHeaders() {
        $headers = new Headers();
        $headers->addHeaderLine('Content-type', 'application/json');
        $this->getRequest()->setHeaders($headers);
    }

    /**
     * Sets requests contents as array.
     *
     * @param $content Content to set.
     * @return void
     */
    public function setJSONRequestContent($content)
    {
        $this->getRequest()->setContent(Json::encode($content));
    }

    /**
     * Returns response content.
     *
     * @return string Response content.
     */
    public function getResponseContent()
    {
        return $this->getResponse()->getContent();
    }
}
