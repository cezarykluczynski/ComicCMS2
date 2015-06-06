"use strict";

admin
    .controller( "ComicsEditController", [ "comics", "$scope", "$rootScope", "$http",
        function ( comics, $scope, $rootScope, $http ) {
        $scope.comics = comics;
        $scope.entity = comics.entity;

        /** Comic entity, either a existing entity or a new one. */
        if ( $scope.ngDialogData.entity ) {
            comics.entity = $scope.ngDialogData.entity;

        } else {
            comics.entity = {};
            comics.entity.title = "";
            comics.entity.description = "";
            comics.entity.tagline = "";
            comics.entity.slug = {
                id: null,
                slug: ""
            };
        }

        /** Authors select. */
        $scope.noAuthor = { id: null, name: "(no author)" };
        $scope.authors = [ $scope.noAuthor ];
        $scope.authors[ "selected" ] = $scope.noAuthor;


        $scope.submitInProgress = false;

        /** Close the dialog. */
        $scope.cancel = function() {
            $rootScope.comicEditDialog.close();
        };

        /** Creates or updates comic entity. */
        $scope.save = function() {
            $scope.submitInProgress = true;

            var url = comics.editingNew() ? "/rest/comic" : "/rest/comic/" + comics.entity.id;
            var $httpMethod = comics.editingNew ? $http.put : $http.post;

            var xhr = $httpMethod( url, comics.entity )
                .then( function ( response ) {
                    $scope.comics.loadComics();
                    $scope.cancel();
                })
                .catch( function ( response ) {
                    //
                })
                .finally( function ( response ) {
                    $rootScope.alertResponse( response );
                    $scope.submitInProgress = false;
                });
        };
    }]);
