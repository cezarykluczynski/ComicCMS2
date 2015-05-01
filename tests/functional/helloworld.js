define([
    "intern!object",
    "intern/chai!assert",
    "require",
    "tests/support/helper"
], function ( registerSuite, assert, require, testHelper ) {
    var signIn = testHelper.getAppUrl( "admin/signin" );
    var mainPage = testHelper.getAppUrl( "" );

    registerSuite({
        name: "Main page.",

        "Main page is loading.": function () {
            return this.remote
                .get( mainPage )
                .setExecuteAsyncTimeout( 10000 )
                .executeAsync( function () {
                    window.scrollTo( 1000, 0 );
                });
                // .setFindTimeout( 3000 )
                // .findByCssSelector( "body.application" )
                // .isDisplayed()
                // .then( function ( visible ) {
                //     assert.ok( visible, "Sign in form loaded." );
                // });
        }
    });

    return;

    registerSuite({
        name: "Admin auth.",

        "Panel is loading.": function () {
            return this.remote
                .get( signIn )
                .setFindTimeout( 3000 )
                .findByCssSelector( "div.admin-auth" )
                .isDisplayed()
                .then( function ( visible ) {
                    assert.ok( visible, "Sign in form loaded." );
                });
        }
    });
});