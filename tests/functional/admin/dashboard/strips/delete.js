define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper",
    "tests/support/commonRoutines"
], function ( registerSuite, assert, testHelper, commonRoutines ) {
    var comicWithStripWithImage;

    registerSuite({
        name: "Strip: delete",

        setup: function () {
            /** Load fixtures. */
            comicWithStripWithImage = testHelper.loadFixtures( "ComicTest.Fixture.ComicWithStripWithImage" );
        },

        teardown: function () {
            /** Unload fixtures. */
            testHelper.unloadFixtures( comicWithStripWithImage );
        },

        "Strip can be deleted.": function () {
            return testHelper.cleanupDashboardTest(
                commonRoutines.openStripList(
                    testHelper.getDashboardAuthorizedAsAdmin( this )
                )
                /** Open first strip. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( ".strips .list-group-item" )
                    .click()
                    .end()
                /** Try to delete strip. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "form[ng-controller=\"StripEditController\"] button.strip-delete" )
                    .click()
                    .end()
                /** Config deletion. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "button.strip-delete-confirm" )
                    .click()
                    .end()
                /** Assert that the success message has been shown. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( ".alertify-log-success" )
                    .getVisibleText()
                    .then( function ( visibleText ) {
                        assert.include( visibleText, "Strip" );
                        assert.include( visibleText, "was deleted." );
                    })
                    .click()
                    .end()
            );
        }
    });
});
