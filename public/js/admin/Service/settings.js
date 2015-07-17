"use string";

admin
    .factory( "settings", [ "$http", function ( $http ) {
        var settings = {};

        settings.loading = false;
        settings.loaded = false;
        settings.list = [];

        settings.loadingStatus = function ( status ) {
            this.loading = status;
            this.loaded = ! status;
        };

        /** Reloads settings. */
        settings.refresh = function ( data ) {
            this.list = data.list;
        };

        settings.loadSettings = function() {
            if ( this.loading ) {
                return;
            }

            var self = this;

            this.loadingStatus( true );

            $http.get( "/rest/settings" )
                .then(function( response ) {
                    self.refresh( response.data );
                    self.loadingStatus( false );
                })
                .catch( function () {
                    self.loadingStatus( false );
                });
       };

        settings.loadSettings();

        return settings;
    }]);