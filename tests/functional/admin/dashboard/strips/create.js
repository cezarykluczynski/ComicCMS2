define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper"
], function ( registerSuite, assert, testHelper ) {
    var title;

    function openStripEdit( context ) {
        return context
            /** Go to "Comics" tab. */
            .findByCssSelector( "ul.root-tabs" )
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "li.tab.comics a" )
                    .click()
                    .end()
                .end()
            .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
            /** Find first comic on the list. It should be entity created in this test. */
            .findByCssSelector( ".admin-comics-list .list-group-item" )
                /** Click for "Edit" and "Delete" buttons to appear. */
                .click()
                .end()
            .findByCssSelector( ".create-strip" )
                .click()
                .end();
    }

    registerSuite({
        name: "Strip: create",

        setup: function () {
            /** Create comic to which strips will be added. */
            title = testHelper.getUniqueString();
            testHelper.createEntity( "Comic.Entity.Comic", { title: title } );
        },

        teardown: function () {
            /** Remove comic create in setup test. */
            testHelper.removeEntity( "Comic.Entity.Comic", { title: title } );
        },

        "Comic can be created.": function () {
            return testHelper.cleanupDashboardTest(
                openStripEdit(
                    testHelper.getDashboardAuthorizedAsAdmin( this )
                )
                /** Assert that the controller is visible. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "form[ng-controller=\"StripEditController\"]" )
                    .isDisplayed()
                    .then( function ( displayed ) {
                        assert.ok( displayed );
                    })
                    .end()
                .findByCssSelector( ".strip-cancel" )
                    .click()
                    .end()
                /** Assert that the controller is no longer visible. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "form[ng-controller=\"StripEditController\"]" )
                    .isDisplayed()
                    .then( function ( displayed ) {
                        assert.notOk( displayed );
                    })
                    .end()
            );
        },
        "Strip can be uploaded.": function () {
            if ( ! testHelper.isLocal() ) {
                this.skip( "Skip until Intern fixes remote uploads." );
                return;
            }

            return testHelper.cleanupDashboardTest(
                openStripEdit(
                    testHelper.getDashboardAuthorizedAsAdmin( this )
                )
                /** Type some title. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "input.strip-title" )
                    .type( "test" )
                    .end()
                /** Assert that strip cannot be saved at this point. */
                .findByCssSelector( ".strip-create" )
                    .isEnabled()
                    .then( function ( enabled ) {
                        assert.notOk( enabled, "\"Create\" button is disabled." )
                    })
                    .end()
                /** Assert that text is displayed in header. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .findByCssSelector( "span.strip-title" )
                    .getVisibleText()
                    .then( function ( text ) {
                        assert.equal( text, "test" );
                    })
                    .end()
                /** Upload file. */
                .setFindTimeout( testHelper.getTimeoutForPageAction() )
                .execute( function () {
                    /** To fix visibility issues on Selenium, file input has to have dimensions and be visible. */
                    var fixes = "input[type=\"file\"][ngf-drop] { \
                        width: 10px !important; \
                        height: 10px !important; \
                        bottom: 0px !important; \
                        left: 0px !important; \
                        position: fixed !important; \
                        z-index: 1 !important; \
                        visibility: visible !important; \
                    }";
                    $( "<style></style>" )
                        .attr( "type", "text/css" )
                        .attr( "rel", "stylesheet" )
                        .text( fixes )
                        .appendTo( "body" );
                })
                .then()
                .findByCssSelector( "input[type=\"file\"][ngf-drop]" )
                    .type( testHelper.getFixturePath( "red.png" ) )
                    .end()
                /** Assert that file has been uploaded. */
                .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
                .findByCssSelector( ".progress.ng-hide .progress-bar" )
                    .getAttribute( "style" )
                    .then( function ( value ) {
                        assert.include( value, "100%" );
                    })
                    .end()
                /** Assert that the thumbnail has size. */
                .findByCssSelector( ".thumbnail" )
                    .getSize()
                    .then( function ( size ) {
                        assert.isAbove( size.width, 99, "Thumbnail width is 100 or greater." );
                        assert.isAbove( size.height, 99, "Thumbnail height is 100 or greater." );
                    })
                    .end()
                /** Assert that strip cannot be saved at this point. */
                .findByCssSelector( ".strip-create" )
                    .isEnabled()
                    .then( function ( enabled ) {
                        assert.ok( enabled, "\"Create\" button is enabled, with title, and image uploaded." )
                    })
                    .end()
            );
        }
    });
});
