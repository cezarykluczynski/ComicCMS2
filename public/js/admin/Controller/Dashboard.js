"use strict";

admin
    .controller( "DashboardController", [ "$scope", "$http", "$sce", "$rootScope", "$templateCache", "Alertify",
    "comics", "users", function( $scope, $http, $sce, $rootScope, $templateCache, Alertify, comics, users ) {
        $scope.users = users;
        $scope.comics = comics;

        $scope.$emit = function (eventName) {
            $rootScope.$emit(eventName);
        };

        /** Compile templates. */
        $( "script[type=\"text/ng-template\"]" ).each(function () {
            var $this = $( this );
            $templateCache.put( $this.attr( "id" ), $this.text() );
        });

        /** Allow all controllers access to unified Alertify wrapper. */
        $rootScope.alertResponse = function ( response ) {
            if ( response.status >= 200 && response.status < 300 ) {
                Alertify.success( response.data && response.data.success ?
                    response.data.success :
                    "Action was successful." );
            } else {
                Alertify.error( response.data && response.data.error ?
                    response.data.error :
                    "Error " + response.status + ": " + response.statusText );
            }
        };

        /** Common helper: various places in code need to blur active element. */
        $rootScope.blurActiveElement = function () {
            document.activeElement.blur();
        };
    }]);
