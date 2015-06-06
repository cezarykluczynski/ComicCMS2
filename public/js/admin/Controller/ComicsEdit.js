"use strict";

admin
    .controller( "ComicsEditController", [ "comics", "$scope", "$rootScope", "$http",
        function ( comics, $scope, $rootScope, $http ) {
        var entity;
        $scope.comics = comics;

        /** Comic entity, either a existing entity or a new one. */
        if ( $scope.ngDialogData.entity ) {
            entity = $scope.ngDialogData.entity;
        } else {
            entity = {};
            entity.id = null;
            entity.title = "";
            entity.description = "";
            entity.tagline = "";
            entity.slug = {
                id: null,
                slug: ""
            };
        }

        comics.editedEntity = entity;

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
            var promise = comics.save().then( function () {
                $scope.cancel();
            });
        };
    }]);
