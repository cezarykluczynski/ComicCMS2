<?php
/**
 * Tests for settings REST controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicTest\Controller;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;
use Zend\Stdlib\Parameters;
use Asset\Entity\Image;

/**
 * @coversDefaultClass \Settings\Controller\SettingsRestController
 * @uses \Application\Controller\AbstractActionController
 * @uses \Application\Service\Authentication
 * @uses \Application\Service\Database
 * @uses \Application\Service\Dispatcher
 * @uses \Settings\Service\Settings
 * @uses \User\Provider\Identity\UserIdentityProvider
 * @uses \User\Provider\Identity\UserIdentityProviderMock
 * @uses \Settings\Service\ManifestService
 */
class SettingsRestControllerTest extends AbstractHttpControllerTestCase
{
    /**
     * Testing an admin controller, admin access is required.
     */
    public function setUp()
    {
        parent::setUp();

        $this->grantAllRolesToUser();
    }

    /**
     * Clean admin access.
     */
    public function tearDown()
    {
        $this->revokeGrantedRoles();
    }

    /**
     * Test if list of settings can be obtained.
     *
     * @covers ::getList
     */
    public function testSettingsListCanBeObtained()
    {
        /** Setup. */
        $this->loadFixtures('SettingsTest\Fixture\Setting');
        $fixtures = $this->getLoadedFixtures();
        $setting = array_pop($fixtures);

        /** Dispatch GET request, expecting list of settings to be returned. */
        $this->getRequest()->setMethod('GET');
        $this->dispatch('/rest/settings');

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(200);

        /** Assert that the array was returned. */
        $this->assertInternalType('array', $response['list']);

        /** Assert that the setting entity from fixtures is among other settings. */
        $this->assertArrayHasKey($setting->name, $response['list']);

        /** Teardown. */
        $this->removeFixtures();
    }

    /**
     * Test if setting entity can be updated.
     *
     * @covers ::update
     */
    public function testSettingEntityCanBeUpdated()
    {
        /** Setup. */
        $this->loadFixtures('SettingsTest\Fixture\Setting');
        $fixtures = $this->getLoadedFixtures();
        $setting = array_pop($fixtures);
        /** @var \Doctrine\ORM\EntityManager. */
        $em = $this->getEntityManager();

        /** Prepare and dispatch PUT request. */
        $this->getRequest()->setMethod('PUT');
        $this->setJSONRequestHeaders();
        $this->setJSONRequestContent([
            'value' => 'changed',
        ]);
        $this->dispatch('/rest/settings/' . $setting->id);

        $response = $this->getJSONResponseAsArray();

        /** Assert controller response. */
        $this->assertResponseStatusCode(200);
        $this->assertEquals('Setting was updated.', $response['success']);

        $setting = $em->find('Settings\Entity\Setting', $setting->id);

        /** Assert entity value change. */
        $this->assertEquals('changed', $setting->value);
    }

    /**
     * Test if non-existing setting cannot be updated.
     *
     * @covers ::update
     */
    public function testNonexistingSettingCannotBeUpdated()
    {
        /** Dispatch invalid request. */
        $this->getRequest()->setMethod('PUT');
        $this->setJSONRequestHeaders();
        $this->dispatch('/rest/settings/' . $this->getHighestInteger());

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        /** Assert status code. */
        $this->assertResponseStatusCode(404);
        /** Assert erroe message. */
        $this->assertEquals('Setting not found.', $response['error']);
    }

}