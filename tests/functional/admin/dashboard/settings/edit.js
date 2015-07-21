define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper",
    "tests/support/commonRoutines"
], function ( registerSuite, assert, testHelper, commonRoutines ) {
    var setting;

    registerSuite({
        name: "Settings: edit",

        setup: function () {
            /** Load fixtures. */
            setting = testHelper.loadFixtures( "SettingsTest.Fixture.Setting" );
        },

        teardown: function () {
            /** Unload fixtures. */
            testHelper.unloadFixtures( setting );
        },

        /**
         * Check is changing setting input shows "Revert" button,
         * and then if clicking "Revert" button reverts input setting input state.
         */
        "Setting can be edited, then reverted.": function () {
            return testHelper.cleanupDashboardTest(
                commonRoutines.editSetting(
                    testHelper.getDashboardAuthorizedAsAdmin( this ), setting
                )
                    /** Assert that changing setting value shows "Revert" button. */
                    .findByCssSelector( "button.revert" )
                        .isDisplayed()
                        .then( function () {
                            assert.ok( true );
                        })
                        /** CLick the button and wait for button to be hidden. */
                        .click()
                        .sleep( 1000 )
                        .isDisplayed( function () {
                            /** Assert that the button was hidden. */
                            assert.notOk( displayed, "Revert button hidden." );
                        })
                        .end()
                    /** Assert that the input state was reverted. */
                    .findByCssSelector( "input[type=\"text\"]" )
                        .getProperty( "value" )
                        .then( function ( value ) {
                            assert.notInclude( value, "additional characters", "Additional characters were removed." );
                            assert.include( value, "test_setting", "Original value was reverted." );
                        })
                        .end()
                    .end()
            );
        },

        "Setting can be edited, then saved.": function () {
            return testHelper.cleanupDashboardTest(
                commonRoutines.editSetting(
                    testHelper.getDashboardAuthorizedAsAdmin( this ), setting
                )
                    /** Assert that changing setting value shows "Save" button. */
                    .findByCssSelector( "button.save" )
                        .isDisplayed()
                        .then( function ( displayed ) {
                            assert.ok( true );
                        })
                        .click()
                        .end()
                    .end()
                /** Wait for message to appear. */
                .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
                .findByCssSelector( ".alertify-log-success" )
                    .sleep( 1000 )
                    .getVisibleText()
                    .then( function ( visibleText ) {
                        assert.include( visibleText, "Setting was updated." );
                    })
            );
        }
    });
});
