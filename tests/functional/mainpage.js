define([
    "intern!object",
    "intern/chai!assert",
    "require",
    "tests/support/helper"
], function ( registerSuite, assert, require, testHelper ) {
    var mainPage = testHelper.getAppUrl( "" );

    registerSuite({
        name: "Main page",

        "Main page is loading.": function () {
            return this.remote
                .get( mainPage )
                .setFindTimeout( 3000 )
                .findByCssSelector( "body.application" )
                .isDisplayed()
                .then( function ( visible ) {
                    assert.ok( visible, "Sign in form loaded." );
                });
        }
    });
});