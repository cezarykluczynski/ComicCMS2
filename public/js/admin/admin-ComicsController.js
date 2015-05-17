"use strict";

admin
    .controller( "ComicsController", [ "$scope", "$rootScope", "ngDialog",
        function( $scope, $rootScope, ngDialog ) {
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

    }])
    .controller( "ComicsCreateDialogController", [ "$scope", "$rootScope", "$http",
        function ( $scope, $rootScope, $http ) {
        /** Pending comics entity. */
        $scope.comics = {};
        $scope.comics.title = "";
        $scope.comics.description = "";
        $scope.comics.tagline = "";
        $scope.comics.slug = "";

        /** Authors select. */
        $scope.noAuthor = { id: null, name: "(no author)" };
        $scope.authors = [ $scope.noAuthor ];
        $scope.authors[ "selected" ] = $scope.noAuthor;

        $scope.submitInProgress = false;

        /** Close the dialog. */
        $scope.cancel = function() {
            $rootScope.comicCreateDialog.close();
        };

        $scope.save = function() {
            $scope.submitInProgress = true;
            var xhr = $http.post( "/rest/comic", $scope.comics )
                .then( function ( response ) {
                    $scope.cancel();
                })
                .finally( function ( response ) {
                    $rootScope.alertResponse( response );
                    $scope.submitInProgress = false;
                });
        };
    }]);