define([
    "intern!object",
    "intern/chai!assert",
    "require",
    "tests/support/helper"
], function ( registerSuite, assert, require, testHelper ) {
    var signIn = testHelper.getAppUrl( "admin/signin" );

    registerSuite({
        name: "Admin auth.",

        /** Check if link to profile configuration is added. */
        "Panel is loading.": function () {
            return this.remote
                .get( signIn )
                .setFindTimeout( 1000 )
                /** Open panel by clicking entry. */
                .findByCssSelector( "div.admin-auth" )
                .isDisplayed()
                .then( function ( visible ) {
                    assert.ok( visible, "Sign in form loaded." );
                });
        }
    });
});