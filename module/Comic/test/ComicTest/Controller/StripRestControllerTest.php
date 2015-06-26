<?php
/**
 * Tests for Comic REST controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace ComicTest\Controller;

use ComicCmsTestHelper\Controller\AbstractHttpControllerTestCase;
use Zend\Stdlib\Parameters;
use Comic\Entity\Comic;
use Comic\Entity\Slug;

/**
 * @coversDefaultClass \Comic\Controller\StripRestController
 * @uses \Application\Controller\AbstractActionController
 * @uses \Application\Service\Authentication
 * @uses \Application\Service\Database
 * @uses \User\Provider\Identity\UserIdentityProvider
 * @uses \User\Provider\Identity\UserIdentityProviderMock
 */
class StripRestControllerTest extends AbstractHttpControllerTestCase
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
     * Test if list of strips associated with comics can be obtained.
     *
     * @covers ::getList
     */
    public function testStripListCanBeObtained()
    {
        /** Setup. */
        $this->loadFixtures('ComicTest\Fixture\ComicWithStrips');
        $fixtures = $this->getLoadedFixtures();

        /**
         * Filter out the comic entity. Only one comic entity is created by {@link \ComicTest\Fixture\ComicWithStrip}.
         */
        $comic = array_filter($fixtures, function ($value) {
            return $value instanceof Comic;
        });
        $comic = array_pop($comic);

        /** Dispatch GET request. */
        $this->getRequest()->setMethod('GET');
        $this->dispatch('/rest/comic/' . $comic->id . '/strip');

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(200);
        $this->assertEquals(10, count($response['list']));

        /** Teardown. */
        $this->removeFixtures();
    }

    /**
     * Test if empty list with error is returned for non-exisitng comic.
     *
     * @covers ::getList
     */
    public function testEmptyListAndErrorIsReturnedForMissingComic()
    {
        /** Dispatch GET request to non-existing ID. */
        $this->getRequest()->setMethod('GET');
        $this->dispatch('/rest/comic/'.$this->getHighestInteger().'/strip');

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(404);
        $this->assertTrue($response['error']);
        $this->assertTrue(empty($response['list']));
    }

    /**
     * Test if empty strip cannot be created for non-existing comic.
     *
     * @covers ::create
     */
    public function testStripCannotBeCreatedForNonExistingComic()
    {
        /** Dispatch PUT request to non-existing ID. */
        $this->getRequest()->setMethod('POST');
        $this->setJSONRequestHeaders();
        $this->setJSONRequestContent([]);
        $this->dispatch('/rest/comic/'.$this->getHighestInteger().'/strip');

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(404);
        $this->assertEquals('Strip cannot be create for non-existing comic.', $response['error']);
    }

    /**
     * Test if empty strip cannot be created for non-existing comic.
     *
     * Doesn't really uses {@link ::getList}, it's just a XDebug bugfix.
     *
     * @covers ::create
     * @uses \Comic\Controller\StripRestController::getList
     */
    public function testStripCanBeCreated()
    {
        /** Setup. */
        $this->loadFixtures('ComicTest\Fixture\Comic');
        $fixtures = $this->getLoadedFixtures();
        $comic = array_pop($fixtures);

        /** Dispatch PUT request to non-existing ID. */
        $this->getRequest()->setMethod('POST');
        $this->setJSONRequestHeaders();
        $this->setJSONRequestContent([
            'images' => [],
            'title' => 'Title',
            'caption' => 'Caption',
        ]);
        $this->dispatch('/rest/comic/'.$comic->id.'/strip');

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(200);
        $this->assertTrue($response['success']);
        $this->assertInternalType('integer', $response['stripId']);

        /** Teardown. */
        $this->removeFixtures();
    }
}
