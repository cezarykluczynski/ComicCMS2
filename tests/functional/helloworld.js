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

        /** Check if link to profile configuration is added. */
        "Main page is loading.": function () {
            return this.remote
                .get( mainPage )
                .setFindTimeout( 1000 )
                /** Check if $panel property is null at first. */
                .execute( function () {
                    document.getElementsByTagName( "table" )[ 0 ].style.width = "500px";
                });
                // .setFindTimeout( 3000 )
                // /** Open panel by clicking entry. */
                // .findByCssSelector( "body.application" )
                // .isDisplayed()
                // .then( function ( visible ) {
                //     assert.ok( visible, "Sign in form loaded." );
                // });
        }
    });

    registerSuite({
        name: "Admin auth.",

        /** Check if link to profile configuration is added. */
        "Panel is loading.": function () {
            return this.remote
                .get( signIn )
                .setFindTimeout( 3000 )
                /** Open panel by clicking entry. */
                .findByCssSelector( "div.admin-auth" )
                .isDisplayed()
                .then( function ( visible ) {
                    assert.ok( visible, "Sign in form loaded." );
                });
        }
    });
});