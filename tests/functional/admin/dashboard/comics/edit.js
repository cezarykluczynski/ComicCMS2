define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper",
    "tests/support/commonRoutines"
], function ( registerSuite, assert, testHelper, commonRoutines ) {
    var comicWithSlug;

    registerSuite({
        name: "Comic: edit",

        setup: function () {
            /** Load fixtures. */
            comicWithSlug = testHelper.loadFixtures( "ComicTest.Fixture.ComicWithSlug" );
        },

        teardown: function () {
            /** Unload fixtures. */
            testHelper.unloadFixtures( comicWithSlug );
        },

        "Comic can be edited.": function () {
            var additionalTitleCharacters = " additional characters";

            return testHelper.cleanupDashboardTest(
                commonRoutines.openStripList(
                    testHelper.getDashboardAuthorizedAsAdmin( this )
                )
                /** Open comic for edit. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( ".comics .list-group-item .comics-edit-open-dialog" )
                    .click()
                    .end()
                /** Add additional characters into title. */
                .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
                .findById( "comics-title" )
                    .type( additionalTitleCharacters )
                    .end()
                .findByCssSelector( "button.comics-save" )
                    /** Click "Save" button and wait for success message. */
                    .click()
                    .end()
                /** Check if the comics was saved. */
                .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
                .findByCssSelector( ".noty_type_success .noty_text" )
                    .sleep( 1000 )
                    .getVisibleText()
                    .then( function ( visibleText ) {
                        assert.equal( visibleText, "Comic was updated." );
                    })
            );
        }
    });
});
