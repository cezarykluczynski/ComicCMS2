define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper",
    "tests/support/commonRoutines"
], function ( registerSuite, assert, testHelper, commonRoutines ) {
    var comicWithStripWithImage;

    registerSuite({
        name: "Strip: list",

        setup: function () {
            /** Load fixtures. */
            comicWithStripWithImage = testHelper.loadFixtures( "ComicTest.Fixture.ComicWithStrips" );
        },

        teardown: function () {
            /** Unload fixtures. */
            testHelper.unloadFixtures( comicWithStripWithImage );
        },

        "Page cannot be change when strip is being edited.": function () {
            return testHelper.cleanupDashboardTest(
                commonRoutines.openStripList(
                    testHelper.getDashboardAuthorizedAsAdmin( this )
                )
                /** Open new entity for edit. */
                .findByCssSelector( ".create-strip" )
                    .click()
                    .end()
                /** Try to open existing entity for edit. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "[ng-controller=\"StripsController\"] .pagination li:not(.active) a:not(.ng-hide)" )
                    .click()
                    .end()
                .sleep( 1000 )
                /** Assert that the error has been shown. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( ".noty_type_error .noty_text" )
                    .sleep( 1000 )
                    .getVisibleText()
                    .then( function ( visibleText ) {
                        assert.equal( visibleText, "Page cannot be change when strip is being edited. " +
                            "Save or discard changes first." );
                    })
            );
        }
    });
});
