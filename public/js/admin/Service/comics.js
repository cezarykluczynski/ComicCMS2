admin
    .factory( "comics", [ "$http", "$rootScope", function( $http, $rootScope ) {
        var comics = {};

        comics.list = [];
        comics.loaded = false;
        comics.loading = false;

        /** Reloads list of comics. */
        comics.refresh = function ( data ) {
            this.list = data.list;
        };

        /** Returns comic that was created lately. */
        comics.getLatest = function () {
            return this.list.sort(function ( a, b ) {
                return a.id > b.id;
            }).slice( -1 ).pop();
        };

        comics.refreshActiveEntityFromList = function () {
            if ( ! this.activeEntity ) {
                return;
            }
            for( var i = 0; i < this.list.length; i++ ) {
                if ( this.list[ i ].id == this.activeEntity.id ) {
                    this.activeEntity = this.list[ i ];
                    break;
                }
            }
        };

        comics.loadingStatus = function ( status ) {
            this.loading = status;
            this.loaded = ! status;
        };

        comics.loadComics = function () {
            var self = this;

            this.loadingStatus( true );

            return $http.get( "/rest/comic" ).then( function ( response ) {
                self.refresh( response.data );
                self.loadingStatus( false );
            });
        };

        comics.editingNew = function () {
            return !comics.editedEntity.id;
        };

        comics.save = function () {
            var self = this;
            var url = this.editingNew() ? "/rest/comic" : "/rest/comic/" + this.editedEntity.id;
            var $httpMethod = this.editingNew() ? $http.post : $http.put;

            this.loadingStatus( true );

            return $httpMethod( url, this.editedEntity )
                .then( function ( response ) {
                    $rootScope.alertResponse( response );
                    return self.loadComics();
                })
                .then( function () {
                    self.refreshActiveEntityFromList();
                    $rootScope.$emit( "comicUpdate" );
                })
                .finally( function () {
                    self.loadingStatus( false );
                })
        };

        comics.delete = function () {
            var self = this;

            this.loadingStatus( true );

            return $http.delete( "/rest/comic/" + this.deleteEntity.id )
                .then( function ( response ) {
                    $rootScope.alertResponse( response );
                })
                .finally( function () {
                    self.loadComics().then( function () {
                        self.refreshActiveEntityFromList();
                        self.loadingStatus( false );
                        $rootScope.$emit( "comicDelete" );
                    });
                });
        };

        comics.loadComics();

        return comics;
    }]);
