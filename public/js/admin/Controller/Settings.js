"use strict";

admin
    .controller( "SettingsController", [ "$rootScope", "$scope", '$http', 'settings',
    function( $rootScope, $scope, $http, settings ) {
        $scope.settings = settings;
    }]);
