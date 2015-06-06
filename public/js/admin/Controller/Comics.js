"use strict";

admin
    .controller( "ComicsController", [ "comics", "strips", "$scope", "$rootScope", "$http", "ngDialog", "$filter",
        function( comics, strips, $scope, $rootScope, $http, ngDialog, $filter ) {
        $scope.comics = comics;

        $rootScope.$on( "openComicEditDialog", function () {
            $scope.openComicEditDialog();
        });

        $scope.openComicEditDialog = function ( entity ) {
            /** Don't create multiple dialogs. */
            if ( $rootScope.comicEditDialog ) {
                return;
            }

            /** Create dialog from a given template. */
            $rootScope.comicEditDialog = ngDialog.open({
                template: "adminComicsEdit",
                className: "ngdialog-theme-default admin-comics-create",
                controller: "ComicsEditController",
                data: {
                    entity: entity
                }
            });

            /** Remove reference to dialog once it's closed, so it can be opened again. */
            $rootScope.comicEditDialog.closePromise.then(function () {
                $rootScope.comicEditDialog = null;
            });

            /** Blur button that opened dialog. */
            $rootScope.blurActiveElement();
        };

        $scope.dirty = false;

        /**
         * If entity is passed, returns true if this comic is currently being edited.
         * If no entity is passed, returns true is any comic is currently beinb edited.
         */
        $scope.activated = function ( entity ) {
            var entityIsSet = !! $scope.comics.activeEntity;
            return entityIsSet && ( typeof entity === "undefined" || entity.id === $scope.comics.activeEntity.id );
        };

        $scope.getActive = function () {
            return $scope.comics.activeEntity;
        };

        $scope.activate = function ( entity, force ) {
            /**
             * Put a comic to edit, but only if the currently edited comic isn't dirty,
             * that is, it doesn't have unsaved changes, and strips are not loading, and current
             * comics is not yet activated.
             */
            if (
                ! $scope.dirty &&
                ! $scope.comics.loading &&
                ! ( force || $scope.activated( entity ) )
            ) {
                $scope.comics.activeEntity = entity;
                $scope.comics.refreshActiveEntityFromList();
                strips.setComicId( entity.id );
            }
        };

        $rootScope.$on( "reloadActiveComic", function () {
            $scope.activate( $scope.comics.activeEntity, true );
        })

    }]);
