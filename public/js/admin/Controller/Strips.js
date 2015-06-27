"use strict";

admin
    .controller( "StripsController", [ "comics", "strips", "$rootScope", "$scope", '$http', "ngDialog",
    function( comics, strips, $rootScope, $scope, $http, ngDialog ) {
        $scope.comics = comics;
        $scope.strips = strips;

        $scope.new = function () {
            strips.new();
            $scope.$broadcast( "comicLoaded" );
        };

        /**
         * If entity is passed, returns true if this comic is currently being edited.
         * If no entity is passed, returns true is any comic is currently beinb edited.
         */
        $scope.activated = function ( entity ) {
            var entityIsSet = !! $scope.strips.entity;
            return entityIsSet && ( typeof entity === "undefined" || entity.id === $scope.strips.entity.id );
        };

        $scope.activate = function ( entity ) {
            if ( $scope.strips.editing() ) {
                
                if ( $scope.strips.entity && $scope.strips.entity.id === entity.id ) {
                    /** This entity is already being edited. */
                    return;
                }

                $rootScope.alertResponse({
                    data: {
                        error: "no"
                    }
                });

                return;
            }

            $scope.strips.load( entity ).then( function () {
                $rootScope.$broadcast( "comicLoaded" );
            });
        };
    }]);
