"use strict";

admin
    .controller( "ComicsController", [ "comics", "strips", "$scope", "$rootScope", "$http", "ngDialog", "$filter",
        function( comics, strips, $scope, $rootScope, $http, ngDialog, $filter ) {
        $scope.comics = comics;
        $scope.strips = strips;
        $scope.dirty = false;

        /** Listener for clicks coming from "Dashboard" tab, when there's is no comics. */
        $rootScope.$on( "openComicEditDialog", function () {
            $scope.openComicEditDialog();
        });

        /** Open comic edit dialog. */
        $scope.openComicEditDialog = function ( entity ) {
            /** Don't create multiple dialogs. */
            if ( $rootScope.comicEditDialog ) {
                return;
            }

            /** Create dialog from a given template. */
            $rootScope.comicEditDialog = ngDialog.open({
                template: "adminComicsEdit",
                className: "ngdialog-theme-default comics-dialog",
                controller: "ComicEditController",
                data: {
                    entity: entity
                }
            });

            /** Remove reference to dialog once it's closed, so it can be opened again. */
            $rootScope.comicEditDialog.closePromise.then(function () {
                $rootScope.comicEditDialog = null;
            });
        };

        /** Open confirmation dialog for comic deletion. */
        $scope.openComicDeleteDialog = function ( entity ) {
            /** Don't create multiple dialogs. */
            if ( $rootScope.comicDeleteDialog ) {
                return;
            }

            /** Create dialog from a given template. */
            $rootScope.comicDeleteDialog = ngDialog.open({
                template: "adminComicsDelete",
                className: "ngdialog-theme-default comics-delete-dialog",
                controller: "ComicDeleteController",
                data: {
                    entity: entity
                }
            });

            /** Remove reference to dialog once it's closed, so it can be opened again. */
            $rootScope.comicDeleteDialog.closePromise.then(function () {
                $rootScope.comicDeleteDialog = null;
            });
        };

        $rootScope.$on( "ngDialog.opened", function () {
            /** Blur button that opened dialog. */
            $rootScope.blurActiveElement();
        });

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
            function activate( entity ) {
                $scope.comics.activeEntity = entity;
                $scope.comics.refreshActiveEntityFromList();
                strips.setComicId( entity.id );
            };

            /**
             * Put a comic to edit, but only if the currently edited comic isn't edited,
             * and strips are not loading, and current comics is not yet activated.
             */
            if ( force ) {
                activate( entity );
            } else if ( strips.editing() || strips.loadingEntity ) {
                $rootScope.error( "cannotChangeComicEntityEditInProgress" );
            } else if ( $scope.comics.loading ) {
                // todo
            } else if ( $scope.activated( entity ) ) {
                /** Requested activation of already active comic, do nothing. */
            } else {
                activate( entity );
            }
        };

        $rootScope.$on( "comicUpdate", function () {
            $scope.activate( $scope.comics.activeEntity, true );
        });

        $rootScope.$on( "comicDelete", function () {
            $scope.comics.activeEntity = null;
        });

        $scope.comics.loadComics().then( function () {
            if ( $scope.comics.list.length === 1 ) {
                $scope.activate( $scope.comics.list[ 0 ] );
            }
        });
    }]);
