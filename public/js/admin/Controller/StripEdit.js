"use strict";

admin
    .controller( "StripEditController", [ "$scope", "$http", "strips", function ( $scope, $http, strips ) {
        $scope.strips = strips;
        $scope.hasFiles = false;

        $scope.cancel = function () {
            $scope.$broadcast( "comicCanceled" );
            strips.cancelEdit();
        };

        $scope.$on( "stripUploadChange", function ( event, loadedFiles ) {
            $scope.hasFiles = !! loadedFiles;
        });

        $scope.canSave = function () {
            return ! $scope.strips.loading && $scope.hasFiles && $scope.strips.entity.title.length;
        };
    }]);
