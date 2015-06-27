"use strict";

var admin = angular.module( "admin",
    [ "angularUtils.directives.dirPagination", "ngDialog", "Alertify", "ngFileUpload", "pascalprecht.translate" ] )
    .config([ "ngDialogProvider", function ( ngDialogProvider ) {
        ngDialogProvider.setDefaults({
            className: "ngdialog-theme-default",
            plain: false,
            showClose: true,
            closeByDocument: false,
            closeByEscape: true
        });
    }])
    .factory("errorHttpInterceptor", [ "$q", "$rootScope", function ( $q, $rootScope ) {
        return {
            responseError: function responseError( response ) {
                if ( response.status >= 400 ) {
                    $rootScope.alertResponse( response );
                }
                return $q.reject( response );
            }
        };
    }])
    .config([ "$httpProvider", function( $httpProvider ) {
        $httpProvider.interceptors.push( "errorHttpInterceptor" );
    }]);
