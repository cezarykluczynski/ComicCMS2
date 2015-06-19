define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper"
], function ( registerSuite, assert, testHelper ) {
    var title, slug;

    registerSuite({
        name: "Comic: delete",

        setup: function () {
            /** Create comic entity to be deleted. */
            title = testHelper.getUniqueString();
            slug = testHelper.getUniqueString();
            testHelper.createEntity( "Comic.Entity.Comic", { title: title } );
        },

        "Comic can be deleted.": function () {
            return testHelper.cleanupDashboardTest(
                testHelper.getDashboardAuthorizedAsAdmin( this )
                /** Open "Comics" tab. */
                .findByCssSelector( "ul.root-tabs" )
                    .setFindTimeout( testHelper.getTimeoutForPageAction() )
                    .findByCssSelector( "li.tab.comics a" )
                        .click()
                        .end()
                    .end()
                .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
                /** Find first comic on the list. It should be entity created in this test. */
                .findByCssSelector( ".admin-comics-list .list-group-item" )
                    /** Click for "Edit" and "Delete" buttons to appear. */
                    .click()
                    /** Assert that the title matches. */
                    .findByCssSelector( ".title" )
                        .getVisibleText()
                        .then( function ( visibleTitle ) {
                            assert.equal( visibleTitle, title );
                        })
                        .end()
                    /** Click "Delete" button. */
                    .findByCssSelector( ".comics-delete" )
                        .click()
                        .end()
                    .end()
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                /** Find dialog and click "Confirm" button. */
                .findByCssSelector( ".comics-delete-dialog" )
                    .findByCssSelector( ".comics-delete-confirm" )
                        .click()
                        .end()
                    .end()
                .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
                /** Check if the comics was created. */
                .findByCssSelector( ".alertify-log-success" )
                    .getVisibleText()
                    .then( function ( visibleText ) {
                        assert.equal( visibleText, "Comic \"" + title + "\" was deleted." );
                    })
                    .end()
            );
        }
    });
});