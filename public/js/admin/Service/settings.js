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
            this.descriptions = data.descriptions;

            this.setOrderAndLabels();
        };

        settings.setOrderAndLabels = function () {
            var self = this;

            this.allHeaders = [];

            /**
             * Go over all settings and order them alphabetically by the group name they belongs to.
             * Store settings descriptions for easy access.
             * Prepare headers information to be filtered in next step.
             */
            var ordered_keys = Object.keys( this.list ).sort( function ( a, b ) {
                var aPrefix = self.descriptions[ a ].group_prefix;
                var bPrefix = self.descriptions[ b ].group_prefix;
                var aLabel = self.descriptions[ a ].label;
                var bLabel = self.descriptions[ b ].label;

                self.descriptions[ a ].value = angular.copy( self.list[ a ] );
                self.descriptions[ b ].value = angular.copy( self.list[ b ] );

                self.allHeaders.push( [ self.descriptions[ a ].group_prefix, self.descriptions[ a ].group_name ] );
                self.allHeaders.push( [ self.descriptions[ b ].group_prefix, self.descriptions[ b ].group_name ] );

                return aPrefix !== bPrefix ? aPrefix > bPrefix : aLabel > bLabel;
            });

            this.allHeaders = _.uniq( this.allHeaders );
            this.headers = {};

            /**
             * Go over all headers and store them, using the first setting name than uses this item as key,
             * and natural text header as value.
             */
            for( var i = 0; i < this.allHeaders.length; i++ ) {
                for ( var j = 0; j < ordered_keys.length; j++ ) {
                    if ( ordered_keys[ j ].indexOf( this.allHeaders[ i ][ 0 ] ) === 0 ) {
                        this.headers[ ordered_keys[ j ] ] = this.allHeaders[ i ][ 1 ];
                        break;
                    }
                }
            }
        };

        /**
         * Saves single setting.
         */
        settings.save = function ( name ) {
            var self = this;
            var id = this.descriptions[ name ].id;
            var data = { value: this.list[ name ] };

            return $http.put( "/rest/settings/" + id, data );
        }

        /** Reaload list of settings. */
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