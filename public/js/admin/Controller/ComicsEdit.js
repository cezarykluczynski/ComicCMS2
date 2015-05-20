"use strict";

admin
    .controller( "ComicsEditController", [ "comics", "$scope", "$rootScope", "$http",
        function ( comics, $scope, $rootScope, $http ) {
        /** Comic entity. */
        $scope.comic = {};
        $scope.comic.title = "";
        $scope.comic.description = "";
        $scope.comic.tagline = "";
        $scope.comic.slug = "";

        /** Authors select. */
        $scope.noAuthor = { id: null, name: "(no author)" };
        $scope.authors = [ $scope.noAuthor ];
        $scope.authors[ "selected" ] = $scope.noAuthor;

        /** Comics model. */
        $scope.comics = comics;

        $scope.submitInProgress = false;

        /** Close the dialog. */
        $scope.cancel = function() {
            $rootScope.comicEditDialog.close();
        };

        /** Save dialog contents. */
        $scope.save = function() {
            $scope.submitInProgress = true;
            var xhr = $http.post( "/rest/comic", $scope.comic )
                .then( function ( response ) {
                    $scope.cancel();
                    $scope.comics.loadComics();
                })
                .finally( function ( response ) {
                    $rootScope.alertResponse( response );
                    $scope.submitInProgress = false;
                });
        };
    }]);
