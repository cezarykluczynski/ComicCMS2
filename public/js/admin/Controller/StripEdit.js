"use strict";

admin
    .controller( "StripEditController", [ "$scope", "$http", "strips", function ( $scope, $http, strips ) {
        $scope.strips = strips;
        $scope.hasFiles = false;

        $scope.cancel = function () {
            $scope.$broadcast( "comicCanceled" );
            $scope.strips.cancelEdit();
        };

        $scope.$on( "stripUploadChange", function ( event, loadedFiles ) {
            $scope.hasFiles = !! loadedFiles;
        });

        $scope.canSave = function () {
            return $scope.hasFiles && $scope.strips.entity.title.length;
        };

        $scope.save = function () {
            $scope.strips.save()
                .then( function () {
                    $scope.cancel();
                    return $scope.strips.loadStrips();
                });
        };
    }]);
