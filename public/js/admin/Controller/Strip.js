"use strict";

admin
    .controller( "StripController", [ "comics", "strips", "$scope", '$http', "ngDialog",
    function( comics, strips, $scope, $http, ngDialog ) {
        $scope.comics = comics;
        $scope.strips = strips;

        $scope.new = function () {
            strips.edit({});
        };
    }]);
