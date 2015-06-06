<?php
/**
 * Abstract HTTP controller, extending {@link \Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase}.
 * It uses a number of helper traits, shared with
 * {@link \ComicCmsTestHelper\ControllerAbstractConsoleControllerTestCase}.
 * This setup is a workaround for a lack of multiple inheritance in PHP.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicCmsTestHelper\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase as BaseTestCase;
use ComicCmsTestHelper\Helper\ApplicationConfig;
use ComicCmsTestHelper\Helper\Constants;
use ComicCmsTestHelper\Helper\Converter;
use ComicCmsTestHelper\Helper\Doctrine;
use ComicCmsTestHelper\Helper\Http;
use ComicCmsTestHelper\Helper\FixtureProvider;
use ComicCmsTestHelper\Helper\UserIdentityProviderMock;

abstract class AbstractHttpControllerTestCase extends BaseTestCase
{
    use ApplicationConfig;
    use Constants;
    use Converter;
    use Doctrine;
    use Http;
    use FixtureProvider;
    use UserIdentityProviderMock;
}