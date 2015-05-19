"use strict";

admin
    .controller( "DashboardController", [ "$scope", "$http", "$sce", "$rootScope", "$templateCache", "Alertify",
    "comics", function( $scope, $http, $sce, $rootScope, $templateCache, Alertify, comics ) {
        $scope.users = {
            current: 1,
            perPage: 10,
            users: [],
            total: 0,
            state: "",
            pageChanged: function(newPage) {
                this.getResultsPage(newPage);
            },
            getResultsPage: function(pageNumber) {
                this.state = "loading";
                $http.get( "/admin/user/widget/users?limit=" + $scope.users.perPage + "&page=" + pageNumber)
                    .then(function(response) {
                        $scope.users.state = "";
                        $scope.users.users = response.data.users;
                        $scope.users.total = response.data.total;
                    });
            }
        };

        $scope.comics = comics;

        $scope.users.getResultsPage( 1 );

        $scope.$emit = function (eventName) {
            $rootScope.$emit(eventName);
        };

        /** Compile templates. */
        $( "script[type=\"text/ng-template\"]" ).each(function () {
            var $this = $( this );
            $templateCache.put( $this.attr( "id" ), $this.text() );
        });

        /** Allow all controllers access to unified Alertify wrapper. */
        $rootScope.alertResponse = function ( response ) {
            if ( response.status >= 200 && response.status < 300 ) {
                Alertify.success( response.data && response.data.success ?
                    response.data.success :
                    "Action was successful." );
            } else {
                Alertify.error( response.data && response.data.error ?
                    response.data.error :
                    "Error " + response.status + ": " + response.statusText );
            }
        };

        /** Common helper: various places in code need to blur active element. */
        $rootScope.blurActiveElement = function () {
            document.activeElement.blur();
        };
    }]);
