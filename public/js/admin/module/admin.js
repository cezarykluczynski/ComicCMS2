"use strict";

var admin = angular.module( "admin",
    [ "angularUtils.directives.dirPagination", "ngDialog", "Alertify", "ui.select" ] )
    .config([ "ngDialogProvider", function ( ngDialogProvider ) {
        ngDialogProvider.setDefaults({
            className: "ngdialog-theme-default",
            plain: false,
            showClose: true,
            closeByDocument: false,
            closeByEscape: true
        });
    }]);
