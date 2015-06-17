"use strict";

admin
    .controller( "StripUploadController", [ "$scope", "$http", "Upload", "strips",
        function ( $scope, $http, Upload, strips ) {
        $scope.strips = strips;

        /** List of all uploaded files. */
        $scope.files = [];

        /** Auto upload: start upload on change. */
        $scope.$watch("files", function () {
            $scope.upload( $scope.files) ;
        });

        /**
         * Return list of loaded files.
         * It's important for "Move up" / "move down" buttons visibility.
         */
        $scope.loadedFilesLength = function () {
            var loaded = 0, i;

            for ( i = 0; i < $scope.files.length; i++ ) {
                /** You can add booleans to integers. */
                loaded += $scope.files[ i ].loaded;
            }

            return loaded;
        };

        $scope.upload = function ( files ) {
            /** Nothing to upload, return. */
            if ( !files || !files.length ) {
                return;
            }

            var i, file;

            for ( i = 0; i < files.length; i++ ) {
                file = files[i];

                /** Skip loaded files. */
                if ( file.loaded ) {
                    continue;
                }

                /** Upload single file. */
                Upload.upload({
                    url: "/rest/upload",
                    file: file
                })
                .progress( function ( evt ) {
                    /** Update progress bar. */
                    var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                    file.progressPercentage = progressPercentage;
                })
                .success(function ( data, status, headers, config ) {
                    file.loaded = true;
                    $scope.upload( files );
                })
                .error( function () {
                    file.loaded = true;
                    $scope.upload( files );
                });

                /** One at the time: break after first file upload is started. */
                break;
            }

        };

        /**
         * Moves file in list up or down.
         */
        $scope.move = function ( direction, file ) {
            var index = $scope.files.indexOf( file );
            var newPosition = index + ( direction === "down" ? 1 : -1 );
            $scope.files.splice( index, 1 );
            $scope.files.splice( newPosition, 0, file );
        };

        /**
         * Deletes file from list.
         */
        $scope.delete = function ( file ) {
            var index = $scope.files.indexOf( file );
            $scope.files.splice( index, 1 );
        };

        /**
         * Removes all files from list.
         */
        $scope.clear = function () {
            $scope.files.splice( 0, $scope.files.length );
        };

        /**
         * Listen to events emited from parent controller,
         * and clear files list if comic edit is canceled.
         */
        $scope.$on( "comicCanceled", function () {
            $scope.clear();
        });
    }]);
