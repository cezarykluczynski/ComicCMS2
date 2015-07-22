"use strict";

admin
    .controller( "DashboardController", [ "$scope", "$http", "$sce", "$rootScope", "$templateCache", "$translate",
    "Alertify", "comics", "users", function( $scope, $http, $sce, $rootScope, $templateCache, $translate, Alertify, comics,
    users ) {
        $scope.users = users;
        $scope.comics = comics;

        $scope.$emit = function ( eventName ) {
            $rootScope.$emit( eventName );
        };

        /** Compile templates. */
        $( "script[type=\"text/ng-template\"]" ).each(function () {
            var $this = $( this );
            $templateCache.put( $this.attr( "id" ), $this.text() );
        });

        /** Allow all controllers access to unified Alertify wrapper. */
        $rootScope.alertResponse = function ( response ) {
            if ( response.status >= 200 && response.status < 300 ) {
                this._alertSuccess( response.data && response.data.success ?
                    response.data.success :
                    "Action was successful." );
            } else {
                this._alertError( response.data && response.data.error ?
                    response.data.error :
                    "Error " + response.status + ": " + response.statusText );
            }
        };

        $rootScope._alertSuccess = function ( message ) {
            this._noty( message, "success" );

        };

        $rootScope._alertError = function ( message ) {
            this._noty( message, "error" );
        };

        $rootScope._noty = function ( message, type ) {
            noty({
                text: message,
                layout: "bottomRight",
                theme: "bootstrapTheme",
                killer: true,
                animation: {
                    easing: 'linear',
                    speed: 0,
                    open: {
                        height: 'toggle'
                    },
                    close: {
                        height: 'toggle'
                    },
                },
                type: type
            });
        }

        $rootScope.ok = true;

        /** Wrapper for convenient error messages. */
        $rootScope.error = function ( message ) {
            $translate( message ).then( function ( message ) {
                $rootScope.alertResponse({
                    data: {
                        error: message
                    }
                });
            });
        };

        /** Wrapper for convenient success messages. */
        $rootScope.success = function ( message ) {
            $translate( message ).then( function ( message ) {
                $rootScope.alertResponse({
                    status: 200,
                    data: {
                        success: message
                    }
                });
            });
        };

        /** Common helper: various places in code need to blur active element. */
        $rootScope.blurActiveElement = function () {
            document.activeElement.blur();
        };
    }]);
