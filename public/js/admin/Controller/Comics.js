"use strict";

admin
    .controller( "ComicsController", [ "comics", "$scope", "$rootScope", '$http', "ngDialog",
        function( comics, $scope, $rootScope, $http, ngDialog ) {
        $rootScope.$on( "openComicCreateDialog", function () {
            $scope.openComicCreateDialog();
        });

        $scope.openComicCreateDialog = function () {
            /** Don't create multiple dialogs. */
            if ( $rootScope.comicCreateDialog ) {
                return;
            }

            /** Create dialog from a given template. */
            $rootScope.comicCreateDialog = ngDialog.open({
                template: "adminComicsCreate",
                className: "ngdialog-theme-default admin-comics-create",
            });

            /** Remove reference to dialog once it's closed, so it can be opened again. */
            $rootScope.comicCreateDialog.closePromise.then(function () {
                $rootScope.comicCreateDialog = null;
            });

            /** Blur button that opened dialog. */
            $rootScope.blurActiveElement();
        };

        $scope.comics = comics;
    }]);
