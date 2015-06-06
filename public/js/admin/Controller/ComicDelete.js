"use strict";

admin
    .controller( "ComicDeleteController", [ "comics", "$scope", "$rootScope",
        function ( comics, $scope, $rootScope ) {
        $scope.comics = comics;
        comics.deleteEntity = $scope.ngDialogData.entity;

        /** Close the dialog. */
        $scope.cancel = function() {
            $rootScope.comicDeleteDialog.close();
        };

        /** Creates or updates comic entity. */
        $scope.delete = function() {
            comics.delete().then( function () {
                $scope.cancel();

                $rootScope.$emit( "comicDeleted" );
            });
        };
    }]);
