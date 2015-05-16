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
                template: "adminComicsCreate"
            });

            /** Remove reference to dialog once it's closed, so it can be opened again. */
            $rootScope.comicCreateDialog.closePromise.then(function () {
                $rootScope.comicCreateDialog = null;
            });

            /** Blur button that opened dialog. */
            document.activeElement.blur();
        };

    }])
    .controller( "ComicsCreateDialogController", [ "$scope", "$rootScope", "$http",
        function ( $scope, $rootScope, $http ) {
        /** Pending comics entity. */
        $scope.comics = {};
        $scope.comics.title = "";
        $scope.comics.description = "";
        $scope.comics.tagline = "";

        $scope.submitInProgress = false;

        /** Close the dialog. */
        $scope.cancel = function() {
            $rootScope.comicCreateDialog.close();
        };

        $scope.save = function() {
            $scope.submitInProgress = true;
            var xhr = $http.put( "/comic/admin/create", $scope.comics )
                .then( function () {
                    $scope.cancel();
                })
                .catch( function ( error ) {
                    $rootScope.showError( error.status + ": " + error.statusText );
                })
                .finally( function () {
                    $scope.submitInProgress = false;
                });
        };
    }]);