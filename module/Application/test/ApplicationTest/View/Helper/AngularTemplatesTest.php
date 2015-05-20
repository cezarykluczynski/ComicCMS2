<?php
/**
 * Tests for view helper for displaying Angular templates.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ApplicationTest\View\Helper;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;
use Zend\Dom\Document;

/**
 * @coversDefaultClass \Application\View\Helper\AngularTemplates
 */
class AngularTemplatesTest extends AbstractHttpControllerTestCase
{
    /**
     * Check if invoking the helper returns a string containing templates.
     *
     * @covers ::__invoke
     * @covers ::gatherTemplates
     * @covers ::render
     */
    public function testTemplatesCanBeObtained()
    {
        $angularTemplates = $this->getApplicationServiceLocator()
            ->get('ViewHelperManager')
            ->get('angularTemplates');

        /** Admin group was the first group created and it'll always be there. */
        $templatesBody = $angularTemplates('admin');

        /** @var \Zend\Dom\Document */
        $document = new Document($templatesBody);

        $scripts = Document\Query::execute('script', $document, Document\Query::TYPE_CSS);
        $wrapper = Document\Query::execute('div.angular-templates', $document, Document\Query::TYPE_CSS);

        /** Assert that the wrapper is present, and there are script tags in wrapper. */
        $this->assertTrue(count($scripts) > 0);
        $this->assertEquals(1, count($wrapper));
    }

    public function testExceptionIsThrownForAnUnknownTemplatesGroup()
    {
        /** Setup. */
        $this->setExpectedException(
            '\Exception',
            'Unkown Angular templates group "not-existing" cannot be rendered.'
        );

        $angularTemplates = $this->getApplicationServiceLocator()
            ->get('ViewHelperManager')
            ->get('angularTemplates');

        /** This should throw an exception about missing templates group. */
        $angularTemplates('not-existing');
    }
}