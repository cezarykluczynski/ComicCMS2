"use strict";

admin
    .controller( "ComicEditController", [ "comics", "$scope", "$rootScope", "$http",
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
            entity.author = "";
            entity.tagline = "";
            entity.slug = {
                id: null,
                slug: ""
            };
        }

        comics.editedEntity = entity;

        /** Close the dialog. */
        $scope.cancel = function() {
            $rootScope.comicEditDialog.close();
        };

        /** Creates or updates comic entity. */
        $scope.save = function() {
            $rootScope.blurActiveElement();
            comics.save().then( function () {
                $scope.cancel();
            });
        };
    }]);
