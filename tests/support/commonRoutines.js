define( [
    "tests/support/helper"
], function( testHelper ) {
    return {
        /** Opens comics edit dialog for new entity. */
        openComicEditDialog: function ( context ) {
            return context.setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
                /** Go to "Comics" tab. */
                .findByCssSelector( "ul.root-tabs" )
                    .setFindTimeout( testHelper.getTimeoutForPageAction() )
                    .findByCssSelector( "li.tab.comics a" )
                        .click()
                        .end()
                    .end()
                /** Wait for comics list to load. */
                .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
                .findByCssSelector( ".comics-list-loading.ng-hide" )
                    .end()
                .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
                .findByCssSelector( "div[ng-controller=\"ComicsController\"] button.comics-create-open-dialog" )
                    .click()
                    .end();
        },

        openStripEdit: function ( context ) {
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
                .findByCssSelector( ".comics .list-group-item" )
                    /** Click for "Edit" and "Delete" buttons to appear. */
                    .click()
                    .end()
                /** Open strip for edit. */
                .findByCssSelector( ".create-strip" )
                    .click()
                    .end();
        },

        openStripList: function ( context ) {
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
                .findByCssSelector( ".comics .list-group-item" )
                    /** Click for "Edit" and "Delete" buttons to appear. */
                    .click()
                    .end();
        }
    };
});
