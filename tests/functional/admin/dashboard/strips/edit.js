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
                .sleep( 1000 )
                /** Assert that the error has been shown. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( ".noty_type_error .noty_text" )
                    .sleep( 1000 )
                    .getVisibleText()
                    .then( function ( visibleText ) {
                        assert.equal( visibleText, "Active strip cannot be changed when other strip is being edited. " +
                            "Save or discard changes first." );
                    })
            );
        }
    });
});
