<?php

namespace ComicCmsTestHelper\Controller;

use Zend\Test\PHPUnit\Controller\AbstractConsoleControllerTestCase as BaseTestCase;
use ComicCmsTestHelper\Helper\ApplicationConfig;
use ComicCmsTestHelper\Helper\Doctrine;
use ComicCmsTestHelper\Helper\FixtureProvider;
use ComicCmsTestHelper\Helper\UserIdentityProviderMock;

abstract class AbstractConsoleControllerTestCase extends BaseTestCase
{
    use ApplicationConfig;
    use Doctrine;
    use FixtureProvider;
    use UserIdentityProviderMock;
}