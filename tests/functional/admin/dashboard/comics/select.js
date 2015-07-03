define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper",
    "tests/support/commonRoutines"
], function ( registerSuite, assert, testHelper, commonRoutines ) {
    var comicNoSelect, comicDoSelect;

    registerSuite({
        name: "Comic: select",

        setup: function () {
            /** Load fixtures. */
            comicNoSelect = testHelper.loadFixtures( "ComicTest.Fixture.Comic" );
            comicDoSelect = testHelper.loadFixtures( "ComicTest.Fixture.Comic" );
        },

        teardown: function () {
            /** Unload fixtures. */
            testHelper.unloadFixtures( comicDoSelect );
            testHelper.unloadFixtures( comicNoSelect );
        },

        "Comic can't be selected if strip from another comic is edited.": function () {
            return testHelper.cleanupDashboardTest(
                commonRoutines.openStripEdit(
                    testHelper.getDashboardAuthorizedAsAdmin( this )
                )
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( ".comics .list-group-item:nth-child(1)" )
                    /** Click for "Edit" and "Delete" buttons to appear. */
                    .click()
                    .end()
                 /** Assert that the error has been shown. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( ".alertify-log-error" )
                    .getVisibleText()
                    .then( function ( visibleText ) {
                        assert.equal( visibleText, "Active comic cannot be change if entity is edited. " +
                            "Save or discard changes first." );
                    })
                    .click()
                    .end()
            );
        },
    });
});
