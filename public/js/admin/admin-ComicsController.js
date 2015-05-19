"use strict";

admin
    .factory( "comics", [ "$http", function( $http ) {
        var comics = {};

        comics.list = {};
        comics.length = 0;

        comics.merge = function ( data ) {
            data.list.forEach(function ( comic ) {
                comics.add( comic );
            });
        };

        comics.add = function ( comic ) {
            comics.list[ comic.id ] = comic;
            comics.length = Object.keys( comics.list ).length;
        }

        comics.getLatest = function () {
            return this.list[ Object.keys( this.list ).sort().pop() ];
        }

        comics.loadComics = function () {
            this.loaded = false;
            $http.get( "/rest/comic" ).then( function ( response ) {
                comics.merge( response.data );
                comics.loaded = true;
            });
        };

        comics.loadComics();

        return comics;
    }])
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
    }])
    .controller( "ComicsEditController", [ "comics", "$scope", "$rootScope", "$http",
        function ( comics, $scope, $rootScope, $http ) {
        /** Pending comics entity. */
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
            $rootScope.comicCreateDialog.close();
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