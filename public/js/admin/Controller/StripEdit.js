"use strict";
"use strict";

admin
    .controller( "StripEditController", [ "$scope", "$http", function ( $scope, $http ) {
        /** Strip entity. */
        $scope.strip = {};
        $scope.strip.title = "";
        $scope.strip.caption = "";
        $scope.strip.images = [];
        $scope.strip.slug = "";
    }]);
