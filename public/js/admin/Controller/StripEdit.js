"use strict";

admin
    .controller( "StripEditController", [ "$scope", "$rootScope", "$http", "strips", "ngDialog",
    function ( $scope, $rootScope, $http, strips, ngDialog ) {
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
            return $scope.hasFiles && $scope.strips.entity.title && $scope.strips.entity.title.length;
        };

        $scope.save = function () {
            $scope.strips.save()
                .then( function () {
                    $scope.cancel();
                    return $scope.strips.loadStrips( 0 );
                });
        };

        /** Open confirmation dialog for comic deletion. */
        $scope.openStripDeleteDialog = function ( entity ) {
            /** Don't create multiple dialogs. */
            if ( $rootScope.stripDeleteDialog ) {
                return;
            }

            /** Create dialog from a given template. */
            $rootScope.stripDeleteDialog = ngDialog.open({
                template: "adminStripDelete",
                className: "ngdialog-theme-default strips-delete-dialog",
                controller: "StripDeleteController"
            });

            /** Remove reference to dialog once it's closed, so it can be opened again. */
            $rootScope.stripDeleteDialog.closePromise.then(function () {
                $rootScope.stripDeleteDialog = null;
            });
        };
    }]);
