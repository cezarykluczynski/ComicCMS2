define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper"
], function ( registerSuite, assert, testHelper ) {
    var mainPage = testHelper.getAppUrl( "" );

    registerSuite({
        name: "Main page",

        "Main page is loading.": function () {
            return this.remote
                .get( mainPage )
                .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
                .findByCssSelector( "body.application" )
                .isDisplayed()
                .then( function ( visible ) {
                    assert.ok( visible, "Main page is visible." );
                });
        }
    });
});
