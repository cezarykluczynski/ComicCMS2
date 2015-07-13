"use strict";

admin
    .controller( "StripDeleteController", [ "strips", "$scope", "$rootScope",
        function ( strips, $scope, $rootScope ) {
        $scope.strips = strips;
        $scope.closing = false;

        /** Close the dialog. */
        $scope.cancel = function() {
            $scope.closing = true;
            $rootScope.stripDeleteDialog.close();
        };

        /** Delete strip entity and reload list afterwards. */
        $scope.delete = function() {
            $scope.strips.delete().then( function () {
                $scope.strips.cancelEdit();
                $scope.strips.changePage( $scope.strips.current );
                $scope.cancel();
            });
        };
    }]);
