define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper"
], function ( registerSuite, assert, testHelper ) {
    /** Opens comics edit dialog for new entity. */
    function openComicEditDialog( context ) {
        return context.setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
            .findByCssSelector( "ul.root-tabs" )
            .getLogsFor( "browser" )
            .then( function ( logs ) {
                console.log( logs )
            })
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "li.tab.comics" )
                    .click()
                    .end()
                .end()
            /** Wait for comics list to load. */
            .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
            .findByCssSelector( ".comics-list-loading.ng-hide" )
                .end()
            .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
            .findByCssSelector( "div[ng-controller=\"ComicsController\"] button.comics-create-open-dialog" )
                .click()
                .end();
    }

    registerSuite({
        name: "Comic: create",

        "Comic can't be created without title and slug.": function () {
            var title = testHelper.getUniqueString();
            var slug = testHelper.getUniqueString();

            return testHelper.cleanupDashboardTest(
                openComicEditDialog(
                    testHelper.getDashboardAuthorizedAsAdmin( this )
                )
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "div.comics-dialog" )
                    /** Assert that the "Create" button is still disabled if there's no title and no slug. */
                    .findByCssSelector( "button.comics-create" )
                        .isEnabled()
                        .then( function ( enabled ) {
                            assert.notOk( enabled, "\"Create\" button is disabled." );
                        })
                        .end()
                    .end()
                    /** Type title. */
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
                    .findById( "comics-title" )
                        .clearValue()
                        .end()
                    /** Type slug. */
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
                openComicEditDialog(
                    testHelper.getDashboardAuthorizedAsAdmin( this )
                )
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "div.comics-dialog" )
                    /** Type title. */
                    .findById( "comics-title" )
                        .type( title )
                        .end()
                    /** Type slug. */
                    .findById( "comics-slug" )
                        .type( title )
                        .end()
                    /** Assert that the "Create" button is now enabled when title and slug were both typed. */
                    .findByCssSelector( "button.comics-create" )
                    .isEnabled()
                        .then( function ( enabled ) {
                            assert.ok( enabled, "\"Create\" button is enabled with title and slug present." );
                        })
                        /** Click "Create" button and wait for success message. */
                        .click()
                        .end()
                    .end()
                .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
                /** Check if the comics was created. */
                .findByCssSelector( ".alertify-log-success" )
                    .getVisibleText()
                    .then( function ( visibleText ) {
                        assert.equal( visibleText, "Comic was created." );
                    })
                    .end()
                /** Teardown. */
                .then( function () {
                    testHelper.removeEntity( "Comic\\Entity\\Comic", { title: title } );
                })
            );
        }
    });
});
