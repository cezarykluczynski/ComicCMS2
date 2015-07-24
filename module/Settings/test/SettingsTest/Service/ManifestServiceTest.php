<?php
/**
 * Tests for manifest parser.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicTest\Controller;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;

/**
 * @coversDefaultClass \Settings\Service\ManifestService
 */
class ManifestServiceTest extends AbstractHttpControllerTestCase
{
    /** @var \Settings\Service\ManifestService */
    protected $manifest;

    /** @var \Zend\Di\ServiceLocator */
    protected $sl;

    public function setUp()
    {
        parent::setUp();

        $this->sl = $this->getApplicationServiceLocator();
        $this->manifest = $this->sl->get('Settings\ExtensionManifest');
    }

    /**
     * Test if list of settings can be obtained.
     *
     * @covers ::setSettings
     * @covers ::setSettingsCollection
     * @covers ::getSettings
     * @covers ::getSettingsCollection
     * @covers ::getFlattenSettings
     */
    public function testGettersAndSetters()
    {
        /** Setup. */
        $this->loadFixtures('SettingsTest\Fixture\Setting');
        $fixtures = $this->getLoadedFixtures();
        $setting = $fixtures[0];

        /** Exercise. */
        $this->manifest->setSettings(['test' => 'test']);
        $this->manifest->setSettingsCollection($fixtures);

        /** Assertion. */
        $this->assertEquals(['test' => 'test'], $this->manifest->getSettings());
        $this->assertEquals($fixtures, $this->manifest->getSettingsCollection());
        $this->assertEquals([$setting->name => $setting->value], $this->manifest->getFlattenSettings());

        /** Teardown. */
        $this->removeFixtures();
    }

    /**
     * Test if main "discover" method works fine.
     *
     * @covers ::discover
     * @covers ::discoverCoreSettings
     * @covers ::discoverTemplatesSettings
     * @covers ::discoverPluginsSettings
     * @covers ::pruneExistingSettings
     * @uses \User\Provider\Identity\UserIdentityProvider
     * @uses \User\Provider\Identity\UserIdentityProviderMock
     * @uses \Settings\Service\Settings
     * @uses \Settings\Service\ManifestService
     * @uses \Settings\Controller\SettingsRestController::getList
     * @uses \Settings\Service\ManifestService::isSettingsIntersection
     * @uses \Application\Service\Authentication
     * @uses \Application\Service\Database
     * @uses \Application\Service\Dispatcher
     */
    public function testManifestsCanBeDiscovered()
    {
        /** Setup. */
        $this->grantAllRolesToUser();
        /** Dispatching settings list will inject settings into service locator. */
        $this->dispatch('/rest/settings');

        $this->manifest->setSettings($this->sl->get('Settings'));
        $this->manifest->discover();
        $this->assertEquals([], $this->manifest->getParsingErrors());

        /** Teardown. */
        $this->revokeGrantedRoles();
    }

    /**
     * Test if configs are correctly parsed into key-value array.
     *
     * @covers ::gatherConfigs
     * @covers ::flattenManifestSettings
     * @covers ::validateFileArray
     */
    public function testCorrectConfigCanBeParsed()
    {
        /** Exercise. */
        $configs = $this->manifest->gatherConfigs(['module', 'Settings', 'test', 'SettingsTest', 'Fixture', 'manifest',
            'correct']);
        $this->manifestSettings = $this->manifest->flattenManifestSettings($configs);

        /** Assertion. */
        $this->assertEquals([
            'group:first_name' => 'name',
            'group:second_name' => '',
        ], $this->manifestSettings);
    }

    /**
     * Test if empty config is parsed withour errors.
     *
     * @covers ::flattenManifestSettings
     * @uses \Settings\Service\ManifestService::gatherConfigs
     * @uses \Settings\Service\ManifestService::getParsingErrors
     * @uses \Settings\Service\ManifestService::validateFileArray
     */
    public function testCorrectEmptyConfigIsParsedWithoutErrors()
    {
        /** Exercise. */
        $configs = $this->manifest->gatherConfigs(['module', 'Settings', 'test', 'SettingsTest', 'Fixture', 'manifest',
            'correct.empty']);
        $this->manifestSettings = $this->manifest->flattenManifestSettings($configs);

        /** Assertion. */
        $this->assertEquals([], $this->manifest->getParsingErrors());
    }

    /**
     * Check if invalid PHP file produces an error.
     *
     * Doesn't really uses {@link ::flattenManifestSettings}, it's just a XDebug bugfix.
     *
     * @covers ::validateFileArray
     * @covers ::getParsingErrors
     * @covers ::gatherConfigs
     * @covers ::error
     * @uses \Settings\Service\ManifestService::flattenManifestSettings
     */
    public function testInvalidPhpProducesAnError()
    {
        /** Exercise. */
        $configs = $this->manifest->gatherConfigs(['module', 'Settings', 'test', 'SettingsTest', 'Fixture', 'manifest',
            'incorrect', 'parse.error']);
        $parsingErrors = $this->manifest->getParsingErrors();

        /** Assertion. */
        $this->assertContains('Error parsing file', $parsingErrors[0]);
        $this->assertContains('incorrect/parse.error', $parsingErrors[0]);
    }

    /**
     * Test if validation errors are shown for missformated manifest fields.
     *
     * @covers ::flattenManifestSettings
     * @uses \Settings\Service\ManifestService::validateFileArray
     * @uses \Settings\Service\ManifestService::gatherConfigs
     * @uses \Settings\Service\ManifestService::getParsingErrors
     * @uses \Settings\Service\ManifestService::error
     */
    public function testMissformatedManifestsAreReported()
    {
        $configs = $this->manifest->gatherConfigs(['module', 'Settings', 'test', 'SettingsTest', 'Fixture', 'manifest',
            'incorrect', 'missformated.fields']);
        $this->manifestSettings = $this->manifest->flattenManifestSettings($configs);
        $parsingErrors = $this->manifest->getParsingErrors();

        $this->assertContains('has to have "name"', $parsingErrors[0]);
        $this->assertContains('has to have "prefix"', $parsingErrors[1]);
        $this->assertContains('contains unnamed setting', $parsingErrors[2]);
        $this->assertContains('contains setting without label', $parsingErrors[3]);
        $this->assertContains('contains unnamed setting', $parsingErrors[4]);
        $this->assertContains('contains setting without label', $parsingErrors[5]);
        $this->assertContains('contains unnamed setting', $parsingErrors[6]);
        $this->assertContains('contains setting without label', $parsingErrors[7]);
    }

    /**
     * Test if validation errors are shown when "settings_group" has invalid type.
     *
     * @covers ::flattenManifestSettings
     * @uses \Settings\Service\ManifestService::validateFileArray
     * @uses \Settings\Service\ManifestService::gatherConfigs
     * @uses \Settings\Service\ManifestService::getParsingErrors
     * @uses \Settings\Service\ManifestService::error
     */
    public function testMissformatedManifestsAreReportedInvalidSettingsGroupsType()
    {
        $configs = $this->manifest->gatherConfigs(['module', 'Settings', 'test', 'SettingsTest', 'Fixture', 'manifest',
            'incorrect', 'invalid.settings.groups.key']);
        $this->manifestSettings = $this->manifest->flattenManifestSettings($configs);
        $parsingErrors = $this->manifest->getParsingErrors();

        $this->assertContains('should be an array, boolean found instead', $parsingErrors[0]);
    }

    /**
     * Test if descriptions are generated properly.
     *
     * @covers ::getDescriptions
     * @uses \Settings\Service\ManifestService::gatherConfigs
     * @uses \Settings\Service\ManifestService::flattenManifestSettings
     * @uses \Settings\Service\ManifestService::validateFileArray
     */
    public function testDescriptionsCanBeObtained()
    {
        /** Setup. */
        $configs = $this->manifest->gatherConfigs(['module', 'Settings', 'test', 'SettingsTest', 'Fixture', 'manifest',
            'correct']);
        $this->manifestSettings = $this->manifest->flattenManifestSettings($configs);

        /** Exercise. */
        $descriptions = $this->manifest->getDescriptions();

        /** Assertion. */
        $this->assertEquals([
            'group:first_name' => [
                'group_name' => 'Settings group name',
                'group_prefix' => 'group:',
                'name' => 'first_name',
                'label' => 'First name',
            ],
            'group:second_name' => [
                'group_name' => 'Settings group name',
                'group_prefix' => 'group:',
                'name' => 'second_name',
                'label' => 'Second name',
            ],
        ], $descriptions);
    }

    /**
     * Test is settings intersections are correcly reported for templates settings overwriting core settings.
     *
     * @covers ::isSettingsIntersection
     * @uses \Settings\Service\ManifestService::getParsingErrors
     * @uses \Settings\Service\ManifestService::error
     */
    public function testNoErrorIsReportedForNonIntersectingSettings()
    {
        /** Exercise. */
        $isSettingsIntersection = $this->manifest->isSettingsIntersection([
            'coreSettings' => [
                'intersect1' => true,
            ],
            'templatesSettings' => [
                'intersect2' => true,
            ],
            'pluginsSettings' => [
                'intersect3' => true,
            ],
        ]);
        $parsingErrors = $this->manifest->getParsingErrors();

        /** Assertion. */
        $this->assertFalse($isSettingsIntersection);
        $this->assertEmpty($parsingErrors);
    }

    /**
     * Test is settings intersections are correcly reported for templates settings overwriting core settings.
     *
     * @covers ::isSettingsIntersection
     * @uses \Settings\Service\ManifestService::getParsingErrors
     * @uses \Settings\Service\ManifestService::error
     */
    public function testSettingsIntersectionsAreReportedForCoreAndTemplates()
    {
        /** Exercise. */
        $isSettingsIntersection = $this->manifest->isSettingsIntersection([
            'coreSettings' => [
                'intersect' => true,
            ],
            'templatesSettings' => [
                'intersect' => true,
            ],
            'pluginsSettings' => [],
        ]);
        $parsingErrors = $this->manifest->getParsingErrors();

        /** Assertion. */
        $this->assertTrue($isSettingsIntersection);
        $this->assertEquals(
            'Some templates define settings keys that were already defined by core application.',
            $parsingErrors[0]
        );
    }

    /**
     * Test is settings intersections are correcly reported for plugins settings overwriting core settings.
     *
     * @covers ::isSettingsIntersection
     * @uses \Settings\Service\ManifestService::getParsingErrors
     * @uses \Settings\Service\ManifestService::error
     */
    public function testSettingsIntersectionsAreReportedForCoreAndPlugins()
    {
        /** Exercise. */
        $isSettingsIntersection = $this->manifest->isSettingsIntersection([
            'coreSettings' => [
                'intersect' => true,
            ],
            'templatesSettings' => [],
            'pluginsSettings' => [
                'intersect' => true,
            ],
        ]);
        $parsingErrors = $this->manifest->getParsingErrors();

        /** Assertion. */
        $this->assertTrue($isSettingsIntersection);
        $this->assertEquals(
            'Some plugins define settings keys that were already defined by core application.',
            $parsingErrors[0]
        );
    }

    /**
     * Test is settings intersections are correcly reported for plugins settings conflicting with templates settings.
     *
     * @covers ::isSettingsIntersection
     * @uses \Settings\Service\ManifestService::getParsingErrors
     * @uses \Settings\Service\ManifestService::error
     */
    public function testSettingsIntersectionsAreReportedForTemplatesAndPlugins()
    {
        /** Exercise. */
        $isSettingsIntersection = $this->manifest->isSettingsIntersection([
            'coreSettings' => [],
            'templatesSettings' => [
                'intersect' => true,
            ],
            'pluginsSettings' => [
                'intersect' => true,
            ],
        ]);
        $parsingErrors = $this->manifest->getParsingErrors();

        /** Assertion. */
        $this->assertTrue($isSettingsIntersection);
        $this->assertEquals(
            'Both plugins and templates define the same settings keys.',
            $parsingErrors[0]
        );
    }
}