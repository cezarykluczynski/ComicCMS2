var adminSignIn = angular.module( "admin-signin", [] )
    .config([ "$httpProvider", function( $httpProvider ) {
        /** Send typical XHR header with ajax requests, so they could be discovered by Zend. */
        $httpProvider.defaults.headers.common[ "X-Requested-With" ] = "XMLHttpRequest";
    }])
