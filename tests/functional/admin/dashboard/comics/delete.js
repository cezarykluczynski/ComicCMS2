define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper"
], function ( registerSuite, assert, testHelper ) {
    registerSuite({
        name: "Comic: delete",

        "Comic can be deleted.": function () {
            var title = testHelper.getUniqueString();
            var slug = testHelper.getUniqueString();

            return testHelper.cleanupDashboardTest(
                testHelper.getDashboardAuthorizedAsAdmin( this )
                .then( function () {
                    /** Create entity. */
                    testHelper.createEntity( "Comic.Entity.Comic", { title: title } );
                })
                .execute( function () {
                    /** Reload page. */
                    location.reload();
                    return true;
                })
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
                    .findByCssSelector( ".comics-delete-confirm" )
                        .isEnabled( function ( enabled ) {
                            /** Assert that "Confirm" button is disabled after clicking. */
                            assert.notOk( enabled );
                        })
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