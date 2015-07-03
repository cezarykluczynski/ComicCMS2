"use strict";

admin
    .controller( "StripUploadController", [ "$scope", "$http", "Upload", "strips",
        function ( $scope, $http, Upload, strips ) {
        $scope.strips = strips;
        $scope.images = [];

        /** Auto upload: start upload on change. */
        $scope.$watch("images", function () {
            $scope.upload( $scope.images );
        });

        /**
         * Return length of list of loaded images.
         * It's important for "Move up" / "Move down" buttons visibility.
         */
        $scope.loadedFilesLength = function () {
            var loaded = 0, i;

            for ( i = 0; i < $scope.images.length; i++ ) {
                /** You can add booleans to integers. */
                loaded += $scope.images[ i ].loaded;
            }

            return loaded;
        };

        $scope.emitState = function () {
            $scope.$emit( "stripUploadChange", $scope.loadedFilesLength() );
        };

        $scope.upload = function ( images ) {
            /** Bind image list back to entity. */
            $scope.strips.entity.images = images;

            /** Nothing to upload, return. */
            if ( ! images || ! images.length ) {
                return;
            }

            var i, image;

            for ( i = 0; i < images.length; i++ ) {
                image = images[ i ];

                /** Skip loaded images. */
                if ( image.loaded ) {
                    continue;
                }

                /** Upload single image. */
                Upload.upload({
                    url: "/rest/upload",
                    file: image
                })
                .progress( function ( evt ) {
                    /** Update progress bar. */
                    var progressPercentage = parseInt( 100.0 * evt.loaded / evt.total );
                    image.progressPercentage = progressPercentage;
                })
                .success(function ( data, status, headers, config ) {
                    if ( data.success ) {
                        image.loaded = true;
                        image.fresh = true;
                        image.caption = "";
                        image.entity = data.image;
                    } else {
                        $scope.delete( image );
                    }

                    $scope.upload( images );
                    $scope.emitState();
                })
                .error( function () {
                    $scope.delete( image );
                    $scope.emitState();
                });

                /** One at the time: break after first image upload is started. */
                break;
            }
        };

        /**
         * Moves image in list up or down.
         */
        $scope.move = function ( direction, image ) {
            var index = $scope.images.indexOf( image );
            var newPosition = index + ( direction === "down" ? 1 : -1 );
            $scope.images.splice( index, 1 );
            $scope.images.splice( newPosition, 0, image );
        };

        /**
         * Deletes image from list.
         */
        $scope.delete = function ( image ) {
            var id = image && image.entity ? image.entity.id : null;
            var fresh =  image && image.fresh;
            var index = $scope.images.indexOf( image );
            $scope.images.splice( index, 1 );
            $scope.emitState();

            /**
             * Delete and ignore result. If it's deleted, it's deleted.
             * If it's not deleted, later a maintenance procedure will be written to cleanup orphaned images.
             */
            if ( id && fresh ) {
                $http.delete( "/rest/image/" + id );
            }
        };

        /**
         * Removes all images from list.
         */
        $scope.clear = function () {
            if ( ! $scope.images ) {
                return;
            }

            $scope.images.splice( 0, $scope.images.length );
            $scope.emitState();
        };

        /**
         * Listen to events emited from parent controller,
         * and clear images list if comic edit is canceled.
         */
        $scope.$on( "comicCanceled", function () {
            $scope.clear();
        });

        /**
         * Listen to events emited from parent controller,
         * and reload images list if comic entity was loaded from server.
         */
        $scope.$on( "comicLoaded", function () {
            /** Bind images from entity. */
            $scope.images = $scope.strips.entity.images;
            $scope.emitState();
        });
    }]);
