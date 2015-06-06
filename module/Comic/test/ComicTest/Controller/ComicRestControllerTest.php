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
 * @coversDefaultClass \Comic\Controller\ComicRestController
 * @uses \Application\Controller\AbstractActionController
 * @uses \Application\Service\Authentication
 * @uses \Application\Service\Database
 * @uses \User\Provider\Identity\UserIdentityProvider
 * @uses \User\Provider\Identity\UserIdentityProviderMock
 */
class ComicRestControllerTest extends AbstractHttpControllerTestCase
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
     * Clean ip admin access.
     */
    public function tearDown()
    {
        $this->revokeGrantedRoles();
    }

    /**
     * Test if comic with all the right data is successfully created.
     *
     * @covers ::create
     */
    public function testComicEntityCanBeCreated()
    {

        /** Setup. */
        $p = new Parameters();
        $p
            ->set('title', 'New comic')
            ->set('tagline', 'New comic tagline')
            ->set('description', 'New comic description.')
            ->set('slug', 'new-comic');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($p);
        $this->dispatch('/rest/comic');

        $response = $this->getJSONResponseAsArray();

        $this->assertResponseStatusCode(201);
        $this->assertTrue(is_int($response['id']), 'Entity created.');

        /** Teardown. */
        $em = $this->getEntityManager();
        $comic = $em->find('Comic\Entity\Comic', $response['id']);
        $em->remove($comic->slug);
        $em->remove($comic);
        $em->flush();
    }

    /**
     * Test if comic is not created when title isn't unique.
     *
     * @covers ::create
     */
    public function testComicEntityCantBeCreatedIfTitleIsNotUnique()
    {
        /** Setup. */
        $em = $this->getEntityManager();
        $comic = new Comic;
        $comic->title = "Taken title";
        $em->persist($comic);
        $em->flush();

        $p = new Parameters();
        $p
            ->set('title', 'Taken title')
            ->set('slug', 'not-taken');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($p);
        $this->dispatch('/rest/comic');

        $response = $this->getJSONResponseAsArray();

        $this->assertResponseStatusCode(409);
        $this->assertEquals(
            'Comic with the title "Taken title" already exists. Choose different title.',
            $response['error']
        );

        /** Teardown. */
        $em->merge($comic);
        $em->remove($comic);
        $em->flush();
    }

    /**
     * Test if comic is not created when slug is used by other comic.
     *
     * @covers ::create
     */
    public function testComicEntityCantBeCreatedIfSlugIsUsedByOtherComic()
    {
        /** Setup. */
        $em = $this->getEntityManager();
        $slug = new Slug;
        $slug->slug = "taken";
        $comic = new Comic;
        $comic->title = "Comic with taken slug";
        $comic->slug = $slug;
        $em->persist($comic);
        $em->flush();

        $p = new Parameters();
        $p
            ->set('title', 'New comic')
            ->set('slug', 'taken');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($p);
        $this->dispatch('/rest/comic');

        $response = $this->getJSONResponseAsArray();

        $this->assertResponseStatusCode(409);
        $this->assertEquals(
            'Comic "Comic with taken slug" already uses slug "taken". Pick another slug.',
            $response['error']
        );

        /** Teardown. */
        $em->remove($comic);
        $em->flush();
    }

    /**
     * Test if comic is created when slug is already cready, but detached from comic.
     *
     * @covers ::create
     */
    public function testComicEntityCanBeCreatedIfSlugExistsAndIsDetached()
    {
        /** Setup. */
        $em = $this->getEntityManager();
        $slug = new Slug;
        $slug->slug = "taken";
        $em->persist($slug);
        $em->flush();

        $p = new Parameters();
        $p
            ->set('title', 'New comic')
            ->set('slug', 'taken');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($p);
        $this->dispatch('/rest/comic');

        $response = $this->getJSONResponseAsArray();

        $this->assertResponseStatusCode(201);
        $this->assertEquals('Comic was created.', $response['success']);

        $comic = $em->find('Comic\Entity\Comic', $response['id']);

        /** Assert that slug entity was merged into newly created comic. */
        $this->assertEquals($slug->id, $comic->slug->id);

        /** Teardown. */
        $em->remove($comic);
        $em->flush();
    }

    /**
     * Test if comic entity can be updated.
     *
     * @covers ::update
     */
    public function testComicEntityCanBeUpdated()
    {
        /** Setup. */
        $em = $this->getEntityManager();
        $slug = new Slug;
        $slug->slug = "test";
        $comic = new Comic;
        $comic->slug = $slug;
        $comic->title = "test";
        $comic->description = "test";
        $comic->tagline = "test";
        $em->persist($comic);
        $em->flush();

        /** Prepare and dispatch PUT request. */
        $this->getRequest()->setMethod('PUT');
        $this->setJSONRequestHeaders();
        $this->setJSONRequestContent([
            'title' => 'changed',
            'description' => 'changed',
            'tagline' => 'changed',
            'slug' => [
                'slug' => 'changed',
            ],
        ]);
        $this->dispatch('/rest/comic/' . $comic->id);

        $response = $this->getJSONResponseAsArray();

        /** Assert controller response. */
        $this->assertResponseStatusCode(200);
        $this->assertTrue(is_int($response['id']), 'Entity created.');
        $this->assertEquals('Comic was updated.', $response['success']);

        $comic = $em->find('Comic\Entity\Comic', $comic->id);
        $slug = $em->find('Comic\Entity\Slug', $comic->slug->id);

        /** Assert entity changes. */
        $this->assertEquals('changed', $comic->title);
        $this->assertEquals('changed', $comic->description);
        $this->assertEquals('changed', $comic->tagline);
        $this->assertEquals('changed', $slug->slug);

        /** Teardown. */
        $em->remove($comic);
        $em->flush();
    }

    /**
     * Test if non-existing comic cannot be updated.
     *
     * @covers ::update
     */
    public function testNonexistingComicCannotBeUpdated()
    {
        /** Dispatch invalid request. */
        $this->getRequest()->setMethod('PUT');
        $this->setJSONRequestHeaders();
        $this->dispatch('/rest/comic/' . $this->getHighestInteger());

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        /** Assert status code. */
        $this->assertResponseStatusCode(404);
        /** Assert erroe message. */
        $this->assertEquals('Comic cannot be updated, because it does not exists.', $response['error']);
    }

    /**
     * Test if comic cannot be updated is slug is used by another comic.
     *
     * @covers ::update
     */
    public function testComicEntityCantBeUpdatedIfSlugIdUsedByAnotherComic()
    {
        /** Setup. */
        $em = $this->getEntityManager();
        $slug = new Slug;
        $slug->slug = "existing";
        $comic = new Comic;
        $comic->slug = $slug;
        $comic->title = "Existing";
        $comic2 = new Comic;
        $comic2->title = "updating";
        $em->persist($comic);
        $em->persist($comic2);
        $em->flush();

        /** Prepare and dispatch PUT request. */
        $this->getRequest()->setMethod('PUT');
        $this->setJSONRequestHeaders();
        $this->setJSONRequestContent([
            'title' => 'changing',
            'slug' => [
                'slug' => 'existing',
            ],
        ]);
        $this->dispatch('/rest/comic/' . $comic2->id);

        $response = $this->getJSONResponseAsArray();

        $this->assertResponseStatusCode(409);
        $this->assertEquals(
            'Comic "Existing" already uses slug "existing". Pick another slug.',
            $response['error']
        );

        /** Teardown. */
        $em->remove($comic);
        $em->remove($comic2);
        $em->flush();
    }

    /**
     * Test if list of comics can be obtained.
     *
     * @covers ::getList
     */
    public function testComicsListCanBeObtained()
    {
        /** Setup. */
        $this->loadFixtures('ComicTest\Fixture\Comics');

        $this->getRequest()->setMethod('GET');
        $this->dispatch('/rest/comic');
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(200);
        $this->assertTrue(!empty($response['list']));

        /** Teardown. */
        $this->removeFixtures();
    }

    /**
     * Test if existing comic can be deleted.
     *
     * @cover ::delete
     */
    public function testExistingComicCanBeDeleted()
    {
        /** Setup. */
        $em = $this->getEntityManager();
        $slug = new Slug;
        $slug->slug = "deleteme";
        $comic = new Comic;
        $comic->slug = $slug;
        $comic->title = "deleteme";
        $em->persist($comic);
        $em->flush();

        $this->getRequest()->setMethod('DELETE');
        $this->dispatch('/rest/comic/'.$comic->id);

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        $this->assertResponseStatusCode(200);
        $this->assertEquals('Comic "deleteme" was deleted.', $response['success']);

        /** Try to find entities that should have been deleted. */
        $comic = $this->em->getRepository('Comic\Entity\Comic')->findOneBy([
            'title' => 'deleteme',
        ]);
        $slug = $this->em->getRepository('Comic\Entity\Slug')->findOneBy([
            'slug' => 'deleteme',
        ]);

        /** Assert that the comic and slug were deleted. */
        $this->assertNull($comic);
        $this->assertNull($slug);
    }

    /**
     * Test if non-existing comic can't be deleted.
     *
     * @cover ::delete
     */
    public function testNonexistingComicCantBeDeleted()
    {
        $this->getRequest()->setMethod('DELETE');
        $this->dispatch('/rest/comic/'.$this->getHighestInteger());

        /** Assert response. */
        $response = $this->getJSONResponseAsArray();
        /** Assert status code. */
        $this->assertResponseStatusCode(404);
        /** Assert erroe message. */
        $this->assertEquals('Comic cannot be deleted, because it does not exists.', $response['error']);
    }
}
