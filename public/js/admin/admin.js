"use strict";

var admin = angular.module( "admin",
    [ "angularUtils.directives.dirPagination", "ngDialog", "Alertify", "ui.select" ] )
    .controller("TabController", function () {
        this.tab = "dashboard";

        this.selectTab = function ( newTab ) {
            this.tab = newTab;
        };

        this.isSelected = function ( tabToCheck ) {
            return this.tab === tabToCheck;
        };
    });

admin.config([ "ngDialogProvider", function (ngDialogProvider) {
    ngDialogProvider.setDefaults({
        className: "ngdialog-theme-default",
        plain: false,
        showClose: true,
        closeByDocument: false,
        closeByEscape: true
    });
}]);
