define([
    "intern!object",
    "intern/chai!assert",
    "require",
    "tests/support/helper"
], function ( registerSuite, assert, require, testHelper ) {
    var signIn = testHelper.getAppUrl( "admin/auth/signin" );

    registerSuite({
        name: "Admin signin",

        /** Check if sign in form is visible. */
        "Sign in form is visible.": function () {
            return this.remote
                .get( signIn )
                .setFindTimeout( 3000 )
                /** Only check if sign in form is vibisle. */
                .findByCssSelector( "div.admin-auth" )
                .isDisplayed()
                .then( function ( visible ) {
                    assert.ok( visible, "Sign in form loaded." );
                });
        },

        /** Check if form cannot be submited with invalid email. */
        "Sign in form cannot be submitted with invalid email.": function () {
            return this.remote
                .get( signIn )
                .setFindTimeout( 3000 )
                .findByCssSelector( "div.admin-auth" )
                    /** Type invalid email. */
                    .findByCssSelector( "input[name=\"email\"]" )
                        .type( "invalidEmail" )
                        .end()
                    .findByCssSelector( "button[type=\"submit\"]" )
                    .isEnabled()
                    .then( function ( disabled ) {
                        /** Assert that submit button is disabled. */
                        assert.notOk( disabled, "Submit button is disabled with invalid email typed." );
                    })
                    .end();
        },

        /** Check if form cannot be submited with empty password. */
        "Sign in form cannot be submitted with no password.": function () {
            return this.remote
                .get( signIn )
                .setFindTimeout( 3000 )
                .findByCssSelector( "div.admin-auth" )
                    /** Type valid, non-empty email. */
                    .findByCssSelector( "input[name=\"email\"]" )
                        .type( "valid@email.com" )
                        .end()
                    .findByCssSelector( "button[type=\"submit\"]" )
                    .isEnabled()
                    .then( function ( disabled ) {
                        /** Assert that submit button is disabled. */
                        assert.notOk( disabled, "Submit button is disabled when no password is typed." );
                    })
                    .end();
        },

        /** Check if form can be submited with credentials, and error is shown when credentials are invalid. */
        "Sign in form with invalid credentials show error when submitted.": function () {
            return this.remote
                .get( signIn )
                .setFindTimeout( 3000 )
                .findByCssSelector( "div.admin-auth" )
                    /** Type valid, non-empty email. */
                    .findByCssSelector( "input[name=\"email\"]" )
                        .type( "valid@email.com" )
                        .end()
                    /** Type valid, non-empty password. */
                    .findByCssSelector( "input[name=\"password\"]" )
                        .type( "invalidPassword" )
                        .end()
                    /** Submit form. */
                    .findByCssSelector( "button[type=\"submit\"]" )
                        .click()
                        .end()
                    .setFindTimeout( 5000 )
                    /** Find form's alert box. */
                    .findByCssSelector( ".alert.alert-danger" )
                    .isDisplayed()
                    .then( function ( displayed ) {
                        /** Assert that the allert box is visible. */
                        assert.ok( displayed );
                    })
                    .getVisibleText()
                    .then( function( text ) {
                        /** Assert that the alert box contains proper message. */
                        assert.include( text, "Authorization failed." );
                    })
                    .end();
        },

        /** Check if form can be submited with credentials, and user is redirected when credentials were valid. */
        "Sign in form with valid credentials signs user in.": function () {
            return this.remote
                .get( signIn )
                .setFindTimeout( 3000 )
                .findByCssSelector( "div.admin-auth" )
                    /** Type valid, non-empty email. */
                    .findByCssSelector( "input[name=\"email\"]" )
                        .type( testHelper.getAdminCredentials().email )
                        .end()
                    /** Type valid, non-empty password. */
                    .findByCssSelector( "input[name=\"password\"]" )
                        .type( testHelper.getAdminCredentials().password )
                        .end()
                    /** Submit form. */
                    .findByCssSelector( "button[type=\"submit\"]" )
                        .click()
                        .end()
                    .end()
                .setFindTimeout( 10000 )
                /** Assert that admin panel Angular app can be found, therefore authentication was successful. */
                .findByCssSelector( "div[ng-app=\"admin\"]" )
                .end();
        }
    });
});