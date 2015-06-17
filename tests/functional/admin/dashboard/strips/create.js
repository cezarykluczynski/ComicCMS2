define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper"
], function ( registerSuite, assert, testHelper ) {
    var title;

    function openStripEdit( context ) {
        return context
            .then( function () {
                /** Create entity. */
                title = testHelper.getUniqueString();
                testHelper.createEntity( "Comic.Entity.Comic", { title: title } );
            })
            .execute( function () {
                /** Reload page. */
                location.reload();
                return true;
            })
            /** Go to "Comics" tab. */
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
                .end()
            .findByCssSelector( ".create-strip" )
                .click()
                .end();
    }

    registerSuite({
        name: "Strip: create",

        "Comic can be created.": function () {
            return testHelper.cleanupDashboardTest(
                openStripEdit(
                    testHelper.getDashboardAuthorizedAsAdmin( this )
                )
                /** Assert that the controller is visible. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "form[ng-controller=\"StripEditController\"]" )
                    .isDisplayed()
                    .then( function ( displayed ) {
                        assert.ok( displayed );
                    })
                    .end()
                .findByCssSelector( ".strip-cancel" )
                    .click()
                    .end()
                /** Assert that the controller is no longer visible. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "form[ng-controller=\"StripEditController\"]" )
                    .isDisplayed()
                    .then( function ( displayed ) {
                        assert.notOk( displayed );
                    })
                    .end()
                .then( function () {
                    testHelper.removeEntity( "Comic.Entity.Comic", { title: title } );
                })
            );
        }
    });
});
