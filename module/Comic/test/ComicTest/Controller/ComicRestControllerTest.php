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
 * @uses \Application\Controller\ApplicationController
 * @uses \User\Provider\Identity\UserIdentityProvider
 * @uses \User\Provider\Identity\UserIdentityProviderMock
 */
class ComicRestControllerTest extends AbstractHttpControllerTestCase
{
    /**
     * Test if comic with all the right data is successfully created.
     *
     * @covers ::create
     */
    public function testComicEntityCanBeCreated()
    {
        /** Setup. */
        $this->grantAllRolesToUser();

        $title =

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
        $this->revokeGrantedRoles();
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

        $this->grantAllRolesToUser();

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
            $response['error'],
            'Comic with the given title already exists. Choose different title.'
        );

        /** Teardown. */
        $em->merge($comic);
        $em->remove($comic);
        $em->flush();
        $this->revokeGrantedRoles();
    }

    /**
     * Test if comic is not created when slug isn't unique.
     *
     * @covers ::create
     */
    public function testComicEntityCantBeCreatedIfSlugIsNotUnique()
    {
        /** Setup. */
        $em = $this->getEntityManager();
        $slug = new Slug;
        $slug->slug = "taken";
        $em->persist($slug);
        $em->flush();

        $this->grantAllRolesToUser();

        $p = new Parameters();
        $p
            ->set('title', 'New comic')
            ->set('slug', 'taken');
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost($p);
        $this->dispatch('/rest/comic');

        $response = $this->getJSONResponseAsArray();

        $this->assertResponseStatusCode(409);
        $this->assertEquals($response['error'], 'The given slug is already in use. Choose another slug.');

        /** Teardown. */
        $em->merge($slug);
        $em->remove($slug);
        $em->flush();
        $this->revokeGrantedRoles();
    }
}