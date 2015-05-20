"use strict";

adminSignIn
    .controller( "SigninController", [ "$scope", "$http", "$window", function( $scope, $http, $window ) {
        $scope.submit = function() {
            if (!$scope.signin.$valid || !$scope.user) {
                return;
            }

            $scope.signin.error = false;
            $scope.pending = true;

            $http({
                    url: "",
                    method: "POST",
                    headers: {"Content-Type": "application/x-www-form-urlencoded"},
                    data: $.param($scope.user)
                })
                .success(function(response) {
                    $scope.signin.success = true;
                    $window.location.href = response.url;
                })
                .error(function(response, status) {
                    $scope.pending = false;
                    $scope.signin.error = true;
                });
        };
    }]);
