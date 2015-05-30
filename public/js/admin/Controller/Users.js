"use strict";

admin
    .controller( "UsersController", [ "users", "$scope", "$rootScope", "$http",
        function( users, $scope, $rootScope, $http ) {
            $scope.users = users;
    }]);
