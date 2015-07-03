define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper",
    "tests/support/commonRoutines"
], function ( registerSuite, assert, testHelper, commonRoutines ) {
    var comicWithStripWithImage;

    registerSuite({
        name: "Strip: edit",

        setup: function () {
            /** Load fixtures. */
            comicWithStripWithImage = testHelper.loadFixtures( "ComicTest.Fixture.ComicWithStripWithImage" );
        },

        teardown: function () {
            /** Unload fixtures. */
            testHelper.unloadFixtures( comicWithStripWithImage );
        },

        "Strip cannot be edited if another entity is edited.": function () {
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
                .findByCssSelector( ".strips .list-group-item" )
                    .click()
                    .end()
                /** Assert that the error has been shown. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( ".alertify-log-error" )
                    .getVisibleText()
                    .then( function ( visibleText ) {
                        assert.equal( visibleText, "Active strip cannot be change if other strip is edited. " +
                            "Save or discard changes first." );
                    })
                    .click()
                    .end()
            );
        }
    });
});
