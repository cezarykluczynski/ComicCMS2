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
use Comic\Entity\Strip;
use Comic\Entity\StripImage;
use Asset\Entity\Image;

/**
 * @coversDefaultClass \Comic\Controller\StripRestController
 * @uses \Application\Controller\AbstractActionController
 * @uses \Application\Service\Authentication
 * @uses \Application\Service\Database
 * @uses \Application\Service\Dispatcher
 * @uses \Settings\Service\Settings
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
     * Test if strip can be created.
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

        /** Dispatch POST request with minimal data. */
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

    /**
     * Test if empty strip cannot be created for non-existing comic.
     *
     * @covers ::create
     */
    public function testStripCannotBeCreatedForNonExistingComic()
    {
        /** Dispatch POST request to non-existing ID. */
        $this->getRequest()->setMethod('POST');
        $this->setJSONRequestHeaders();
        $this->setJSONRequestContent([]);
        $this->dispatch('/rest/comic/'.$this->getHighestInteger().'/strip');

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(404);
        $this->assertEquals('Strip cannot be created for non-existing comic.', $response['error']);
    }

    /**
     * Test if strip can be updated.
     *
     * Doesn't really uses {@link ::create}, it's just a XDebug bugfix.
     *
     * @covers ::update
     * @uses \Comic\Controller\StripRestController::create
     */
    public function testStripCanBeUpdated()
    {
        /** Setup. */
        $this->loadFixtures('ComicTest\Fixture\ComicWithStripWithImage');
        $this->loadFixtures('ComicTest\Fixture\ComicWithStripWithImage');
        $fixtures = $this->getLoadedFixtures();

        $comic = null;
        $strip = null;
        /** @var \Comic\Entity\Strip[] */
        $stripImages = [];
        /** @var \Asset\Entity\Image[] */
        $images = [];

        foreach($fixtures as $fixture)
        {
            if ($fixture instanceof Comic && !$comic)
            {
                /** @var \Comic\Entity\Comic */
                $comic = $fixture;
            }

            if ($fixture instanceof Strip && !$strip)
            {
                /** @var \Comic\Entity\Strip */
                $strip = $fixture;
            }

            if ($fixture instanceof StripImage)
            {
                $stripImages[] = $fixture;
            }

            if ($fixture instanceof Image)
            {
                $images[] = $fixture;
            }
        }

        /** Dispatch PUT request with new data. */
        $this->getRequest()->setMethod('PUT');
        $this->setJSONRequestHeaders();
        $this->setJSONRequestContent([
            'images' => [
                [
                    'caption' => 'New image',
                    'entity' => [
                        'id' => $images[1]->id,
                    ],
                ],
            ],
            'title' => 'New title',
            'caption' => 'New caption',
        ]);
        $this->dispatch('/rest/comic/'.$comic->id.'/strip/'.$strip->id);

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(200);
        $this->assertTrue($response['success']);
        $this->assertInternalType('integer', $response['stripId']);

        $em = $this->getEntityManager();

        $strip = $em->find('Comic\Entity\Strip', $strip->id);

        $this->assertEquals('New title', $strip->title);
        $this->assertEquals('New caption', $strip->caption);

        $this->assertEquals(1, $strip->images->count());

        /** Teardown. */
        $this->removeFixtures();
    }

    /**
     * Test if empty strip cannot be updated for non-existing comic.
     *
     * @covers ::update
     */
    public function testStripCannotBeUpdateForNonExistingComic()
    {
        /** Dispatch PUT request to non-existing ID. */
        $this->getRequest()->setMethod('PUT');
        $this->setJSONRequestHeaders();
        $this->setJSONRequestContent([]);
        $this->dispatch('/rest/comic/'.$this->getHighestInteger().'/strip/1');

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(404);
        $this->assertEquals('Strip cannot be updated for non-existing comic.', $response['error']);
    }

    /**
     * Test if strip can be retrieved.
     *
     * @covers ::get
     * @uses \Comic\Controller\StripRestController::getList
     */
    public function testStripCanBeRetrieved()
    {
        /** Setup. */
        $this->loadFixtures('ComicTest\Fixture\ComicWithStripWithImage');
        $fixtures = $this->getLoadedFixtures();

        foreach($fixtures as $fixture)
        {
            if ($fixture instanceof Comic)
            {
                /** @var \Comic\Entity\Comic */
                $comic = $fixture;
            }

            if ($fixture instanceof Strip)
            {
                /** @var \Comic\Entity\Strip */
                $strip = $fixture;
            }

            if ($fixture instanceof StripImage)
            {
                /** @var \Comic\Entity\StripImage */
                $stripImage = $fixture;
            }

            if ($fixture instanceof Image)
            {
                /** @var \Asset\Entity\Image */
                $image = $fixture;
            }
        }

        /** @var \Asset\Service\UploadCdn */
        $cdn = $this->getApplicationServiceLocator()->get('Asset\UploadCdn');

        /** Dispatch GET request. */
        $this->getRequest()->setMethod('GET');
        $this->dispatch('/rest/comic/'.$comic->id.'/strip/'.$strip->id);

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(200);

        /** Assert strip-related properties. */
        $this->assertEquals($strip->id, $response['entity']['id']);
        $this->assertEquals($strip->title, $response['entity']['title']);
        $this->assertEquals($strip->caption, $response['entity']['caption']);

        /** Assert image-related properties. */
        $this->assertEquals($stripImage->image->id, $response['entity']['images'][0]['entity']['id']);
        $this->assertEquals($stripImage->caption, $response['entity']['images'][0]['caption']);
        $this->assertEquals(
            $cdn->canonicalRelativePathToUri($image->canonicalRelativePath),
            $response['entity']['images'][0]['entity']['uri']
        );

        /** Teardown. */
        $this->removeFixtures();
    }

    /**
     * Test if strip cannot be retrieved for non-existing comic.
     *
     * @covers ::get
     */
    public function testStripCannotBeRetrievedForNonExistingComic()
    {
        /** Dispatch GET request to non-existing ID. */
        $this->getRequest()->setMethod('GET');
        $this->dispatch('/rest/comic/'.$this->getHighestInteger().'/strip/1');

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(404);
        $this->assertEquals('Strip cannot be retrieved for non-existing comic.', $response['error']);
    }

    /**
     * Test if strip cannot be retrieved if it don't exist.
     *
     * @covers ::get
     */
    public function testNonExistingStripCannotBeRetrieved()
    {
        /** Setup. */
        $this->loadFixtures('ComicTest\Fixture\Comic');
        $fixtures = $this->getLoadedFixtures();
        $comic = array_pop($fixtures);

        /** Dispatch GET request to non-existing ID. */
        $this->getRequest()->setMethod('GET');
        $this->dispatch('/rest/comic/'.$comic->id.'/strip/'.$this->getHighestInteger());

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(404);
        $this->assertEquals('Strip not found.', $response['error']);

        /** Teardown. */
        $this->removeFixtures();
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
     * Test if strip can be deleted.
     *
     * @cover ::delete
     */
    public function testStripCanBeDeleted()
    {
        $this->loadFixtures('ComicTest\Fixture\ComicWithStrips');
        $fixtures = $this->getLoadedFixtures();

        foreach($fixtures as $fixture)
        {
            if ($fixture instanceof Comic)
            {
                /** @var \Comic\Entity\Comic */
                $comic = $fixture;
            }

            if ($fixture instanceof Strip)
            {
                /** @var \Comic\Entity\Strip */
                $strip = $fixture;
            }
        }

        /** Dispatch DELETE request. */
        $this->getRequest()->setMethod('DELETE');
        $this->dispatch('/rest/comic/'.$comic->id.'/strip/'.$strip->id);

        $em = $this->getEntityManager();

        /** @var null */
        $strip = $em->find('Comic\Entity\Strip', $strip->id);

        $this->assertNull($strip);

        /** Teardown. */
        $this->removeFixtures();
    }

    /**
     * Test if an strip from non-existing comic cannot be deleted.
     *
     * @cover ::delete
     */
    public function testStripCannotBeDeletedForNonExistingComic()
    {
        $this->getRequest()->setMethod('DELETE');
        $this->dispatch('/rest/comic/'.$this->getHighestInteger().'/strip/1');

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        /** Assert status code. */
        $this->assertResponseStatusCode(404);
        /** Assert erroe message. */
        $this->assertEquals('Strip cannot be deleted for non-existing comic.', $response['error']);
    }


    /**
     * Test if non-existing strip cannot be deleted.
     *
     * @covers ::delete
     */
    public function testNonExistingStripCannotBeDeleted()
    {
        /** Setup. */
        $this->loadFixtures('ComicTest\Fixture\Comic');
        $fixtures = $this->getLoadedFixtures();
        $comic = array_pop($fixtures);

        /** Dispatch GET request to non-existing ID. */
        $this->getRequest()->setMethod('DELETE');
        $this->dispatch('/rest/comic/'.$comic->id.'/strip/'.$this->getHighestInteger());

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(404);
        $this->assertEquals('Strip not found.', $response['error']);

        /** Teardown. */
        $this->removeFixtures();
    }
}
