"use string";

admin
    .factory( "users", [ "$http", function ( $http ) {
        var users = {
            current: 1,
            limit: 10,
            list: [],
            total: 0,
            state: "",
            loaded: false,
            loading: false
        };

        users.changePage = function( newPage ) {
            var offset = ( newPage - 1 ) * this.limit;
            this.load( offset );
        };

        users.load = function( pageNumber ) {
            if ( this.loading ) {
                return;
            }

            this.loaded = false;
            this.loading = true;
            this.total = 0;

            $http.get( "/rest/user?limit=" + users.limit + "&offset=" + pageNumber)
                .then(function(response) {
                    users.state = "";
                    users.list = response.data.users;
                    users.total = response.data.total;
                })
                .finally( function () {
                    users.loaded = true;
                    users.loading = false;
                });
       };

        users.changePage( 1 );

        return users;
    }]);