"use strict";
"use strict";

admin
    .controller( "StripEditController", [ "$scope", "$http", "strips", function ( $scope, $http, strips ) {
        $scope.strips = strips;

        $scope.cancel = function () {
            strips.cancelEdit();
        };
    }]);
