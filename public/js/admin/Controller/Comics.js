"use strict";

admin
    .controller( "ComicsController", [ "comics", "$scope", "$rootScope", '$http', "ngDialog",
        function( comics, $scope, $rootScope, $http, ngDialog ) {
        $rootScope.$on( "openComicCreateDialog", function () {
            $scope.openComicCreateDialog();
        });

        $scope.openComicCreateDialog = function () {
            /** Don't create multiple dialogs. */
            if ( $rootScope.comicEditDialog ) {
                return;
            }

            /** Create dialog from a given template. */
            $rootScope.comicEditDialog = ngDialog.open({
                template: "adminComicsCreate",
                className: "ngdialog-theme-default admin-comics-create",
            });

            /** Remove reference to dialog once it's closed, so it can be opened again. */
            $rootScope.comicEditDialog.closePromise.then(function () {
                $rootScope.comicEditDialog = null;
            });

            /** Blur button that opened dialog. */
            $rootScope.blurActiveElement();
        };

        $scope.comic = {};
        $scope.comic.entity = null;
        $scope.comic.dirty = false;

        $scope.activated = function ( entity ) {
            return $scope.comic.entity && entity.id === $scope.comic.entity.id;
        }

        $scope.activate = function ( entity ) {
            if ( ! $scope.comic.dirty ) {
                $scope.comic.entity = entity;
            }
        };

        $scope.comics = comics;
    }]);
