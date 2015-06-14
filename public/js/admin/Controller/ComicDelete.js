"use strict";

admin
    .controller( "ComicDeleteController", [ "comics", "$scope", "$rootScope",
        function ( comics, $scope, $rootScope ) {
        $scope.comics = comics;
        $scope.closing = false;
        comics.deleteEntity = $scope.ngDialogData.entity;

        /** Close the dialog. */
        $scope.cancel = function() {
            $scope.closing = true;
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
