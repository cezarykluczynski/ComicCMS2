<?php
/**
 * Abstract console controller, in place of {@link \Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase}.
 * It uses a number of helper traits, shared with
 * {@link \ComicCmsTestHelper\ControllerAbstractHttpControllerTestCase}.
 * This setup is a workaround for a lack of multiple inheritance in PHP.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicCmsTestHelper\Controller;

use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase as BaseTestCase;
use ComicCmsTestHelper\Helper\ApplicationConfig;
use ComicCmsTestHelper\Helper\Converter;
use ComicCmsTestHelper\Helper\Doctrine;
use ComicCmsTestHelper\Helper\FixtureProvider;
use ComicCmsTestHelper\Helper\UserIdentityProviderMock;

abstract class AbstractConsoleControllerTestCase extends BaseTestCase
{
    use ApplicationConfig;
    use Converter;
    use Doctrine;
    use FixtureProvider;
    use UserIdentityProviderMock;
}