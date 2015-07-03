define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper",
    "tests/support/commonRoutines"
], function ( registerSuite, assert, testHelper, commonRoutines ) {

    registerSuite({
        name: "Comic: create",

        "Comic can't be created without title and slug.": function () {
            var title = testHelper.getUniqueString();
            var slug = testHelper.getUniqueString();

            return testHelper.cleanupDashboardTest(
                commonRoutines.openComicEditDialog(
                    testHelper.getDashboardAuthorizedAsAdmin( this )
                )
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "div.comics-dialog" )
                    /** Assert that the "Create" button is still disabled if there's no title and no slug. */
                    .setFindTimeout( testHelper.getTimeoutForPageAction() )
                    .findByCssSelector( "button.comics-create" )
                        .isEnabled()
                        .then( function ( enabled ) {
                            assert.notOk( enabled, "\"Create\" button is disabled." );
                        })
                        .end()
                    .end()
                    /** Type title. */
                    .setFindTimeout( testHelper.getTimeoutForPageAction() )
                    .findById( "comics-title" )
                        .type( title )
                        .end()
                    /** Assert that the "Create" button is still disabled after title was typed. */
                    .findByCssSelector( "button.comics-create" )
                    .isEnabled()
                        .then( function ( enabled ) {
                            assert.notOk( enabled, "\"Create\" button is disabled with only title present." );
                        })
                        .end()
                    /** Clear title. */
                    .setFindTimeout( testHelper.getTimeoutForPageAction() )
                    .findById( "comics-title" )
                        .clearValue()
                        .end()
                    /** Type slug. */
                    .setFindTimeout( testHelper.getTimeoutForPageAction() )
                    .findById( "comics-slug" )
                        .type( title )
                        .end()
                    /**
                     * Assert that the "Create" button is still disabled after title was removed,
                     * and slug was typed.
                     */
                    .findByCssSelector( "button.comics-create" )
                    .isEnabled()
                        .then( function ( enabled ) {
                            assert.notOk( enabled, "\"Create\" button is disabled with only slug present." );
                        })
                        .end()
                    .end()
            );
        },

        "Comic can be created with title and slug.": function () {
            var title = testHelper.getUniqueString();
            var slug = testHelper.getUniqueString();

            return testHelper.cleanupDashboardTest(
                commonRoutines.openComicEditDialog(
                    testHelper.getDashboardAuthorizedAsAdmin( this )
                )
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "div.comics-dialog" )
                    /** Type title. */
                    .setFindTimeout( testHelper.getTimeoutForPageAction() )
                    .findById( "comics-title" )
                        .type( title )
                        .end()
                    /** Type slug. */
                    .setFindTimeout( testHelper.getTimeoutForPageAction() )
                    .findById( "comics-slug" )
                        .type( title )
                        .end()
                    /** Assert that the "Create" button is now enabled when title and slug were both typed. */
                    .setFindTimeout( testHelper.getTimeoutForPageAction() )
                    .findByCssSelector( "button.comics-create" )
                    .isEnabled()
                        .then( function ( enabled ) {
                            assert.ok( enabled, "\"Create\" button is enabled with title and slug present." );
                        })
                        /** Click "Create" button and wait for success message. */
                        .click()
                        .end()
                    .end()
                /** Check if the comics was created. */
                .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
                .findByCssSelector( ".alertify-log-success" )
                    .getVisibleText()
                    .then( function ( visibleText ) {
                        assert.equal( visibleText, "Comic was created." );
                    })
                    .click()
                    .end()
                /** Teardown. */
                .then( function () {
                    testHelper.removeEntity( "Comic.Entity.Comic", { title: title } );
                })
            );
        }
    });
});
