"use strict";

admin
    .controller( "SettingsController", [ "$rootScope", "$scope", '$http', 'settings',
    function( $rootScope, $scope, $http, settings ) {
        $scope.settings = settings;

        /** Whether the setting value changed from what was loaded. */
        $scope.dirty = function ( name ) {
            return $scope.settings.descriptions[ name ].value !== $scope.settings.list[ name ];
        };

        /** Revert setting to the state it was when loade. */
        $scope.revert = function ( name ) {
            $scope.settings.list[ name ] = angular.copy( $scope.settings.descriptions[ name ].value );
        };

        /** Return setting pretty description. */
        $scope.getDescription = function ( name ) {
            return $scope.settings.descriptions[ name ] ? $scope.settings.descriptions[ name ].label : name;
        };

        $scope.getId = function ( name ) {
            return $scope.settings.descriptions[ name ] ? $scope.settings.descriptions[ name ].id : null;
        };

        /** Returns header, is applicable, or undefined, if no header should be shown before given setting. */
        $scope.getHeader = function ( name ) {
            return $scope.settings.headers[name];
        };

        /** Whether the setting is being saved. */
        $scope.saving = function ( name ) {
            return !! $scope.settings.descriptions[ name ].saving;
        };

        /** Saves setting entity. */
        $scope.save = function ( name ) {
            $scope.settings.descriptions[ name ].saving = true;

            return $scope.settings.save( name )
                .then( function () {
                    /** When entity is saved, the changed name is now saved as original name. */
                    $scope.settings.descriptions[ name ].value = angular.copy( $scope.settings.list[ name ] );
                })
                .finally( function () {
                    $scope.settings.descriptions[ name ].saving = false;
                });
        };
    }]);
