define([
    "intern!object",
    "intern/chai!assert",
    "tests/support/helper"
], function ( registerSuite, assert, testHelper ) {
    registerSuite({
        name: "Dashboard",

        /** Check if dashboard is visible. */
        "Dashboard is visible.": function () {
            return testHelper.cleanupDashboardTest(
                testHelper.getDashboardAuthorizedAsAdmin( this )
                .setFindTimeout( testHelper.getTimeoutForAjaxRequests() )
                .findByCssSelector( "div[ng-app=\"admin\"]" )
                .end()
            );
        },
    });
});
