"use strict";

admin
    .controller( "ComicsController", [ "$scope", "$rootScope", "ngDialog",
        function( $scope, $rootScope, ngDialog ) {
        $rootScope.$on( "openComicCreateDialog", function () {
            $scope.openComicCreateDialog();
        });

        $scope.openComicCreateDialog = function () {
            /** Don't create multiple dialogs. */
            if ($rootScope.comicCreateDialog ) {
                return;
            }

            $rootScope.comicCreateDialog = ngDialog.open({
                template: "adminComicsCreate",
                controller: "ComicsCreateDialogController"
            });

            /** Remove reference to dialog once it's closed, so it can be opened again. */
            $rootScope.comicCreateDialog.closePromise.then(function () {
                $rootScope.comicCreateDialog = null;
            });

            /** Blur button that opened dialog. */
            document.activeElement.blur();
        }
    }])
    .controller( "ComicsCreateDialogController", [ "$scope",
        function ( $scope ) {
    }]);