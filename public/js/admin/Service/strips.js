admin
    .factory( "strips", [ "$http", "$httpParamSerializer", "$q", "$rootScope",
    function( $http, $httpParamSerializer, $q, $rootScope ) {
        var strips = {};

        strips.list = [];
        strips._editing = false;
        strips.entity = {};

        strips.limit = 5;
        strips.current = 1;
        strips.total = 0;

        strips.changePage = function( newPage ) {
            var offset = ( newPage - 1 ) * this.limit;
            this.current = newPage;
            this.loadStrips( offset );
        };;

        strips.xhr = {};

        /**
         * Reload entity list.
         */
        strips.refresh = function ( data ) {
            this.list = data.list;
            this.total = data.total;
        };

        strips.editing = function ( editing ) {
            if ( undefined === editing ) {
                return this._editing;
            }

            this._editing = editing;
        }

        strips.loadingStatus = function ( status ) {
            this.loading = status;
            this.loaded = ! status;
        };

        strips.savingStatus = function ( status ) {
            this.saving = status;
        };

        strips.setComicId = function ( comicId ) {
            this.comicId = comicId;
            this.loadStrips();
        };

        strips.getComicUri = function () {
            return "/rest/comic/" + this.comicId + "/strip";
        };

        strips.getListUri = function ( offset ) {
            return this.getComicUri() + "?" + $httpParamSerializer({
                offset: offset,
                limit: this.limit
            });
        };

        strips.loadStrips = function ( offset ) {
            var self = this;

            function loadStrips( offset ) {
                self.loadingStatus( true );
                self.xhr.loadStripsCancel = $q.defer();
                var uri = self.getListUri( offset );
                self.xhr.loadStrips = $http.get( uri, {
                        timeout: self.xhr.loadStripsCancel.promise
                    })
                    .then( function ( response ) {
                        self.refresh( response.data );
                    })
                    .finally( function () {
                        self.loadingStatus( false );
                    });

                return self.xhr.loadStrips;
            }

            if ( this.xhr.loadStrips && this.xhr.loadStrips.$$state.status < 2 ) {
                this.xhr.loadStrips.finally( function () {
                    return loadStrips( offset );
                });
                this.xhr.loadStripsCancel.resolve();
            } else {
                loadStrips( offset );
            }
        };

        $rootScope.$on( "reloadStrips", function () {
            strips.loadStrips();
        });

        strips.new = function () {
            var entity = {
                id: null,
                title: "",
                caption: "",
                images: []
            };

            this.edit( entity );
        };

        strips.load = function ( entity ) {
            var self = this;

            this.loadingEntity = entity.id;

            return $http.get( this.getComicUri() + "/" + entity.id )
                .then( function ( response ) {
                    self.decorateLoadedImages( response.data.entity );
                    self.edit( response.data.entity );
                })
                .finally( function () {
                    self.loadingEntity = false;
                });
        };

        strips.edit = function( entity ) {
            this.entity = entity;
            this.editing( true );
        };

        strips.delete = function () {
            var self = this;

            this.loadingStatus( true );

            return $http.delete( this.getComicUri()  + "/" + this.entity.id )
                .then( function ( response ) {
                    $rootScope.alertResponse( response );
                });
        };

        /**
         * Cancel edit.
         */
        strips.cancelEdit = function () {
            this.editing( false );
            this.entity = {};
        };

        strips.save = function () {
            var self = this;
            var $httpMethod = this.entity.id ? $http.put : $http.post;
            var uri = this.getComicUri() + ( this.entity.id ? "/" + this.entity.id : "" );

            this.savingStatus( true );

            return $httpMethod( uri, this.pruneEntityForSave( this.entity ) )
                .then( function () {
                    self.editing( false );
                })
                .finally( function () {
                    self.savingStatus( false );
                });
        };

        strips.pruneEntityForSave = function ( base ) {
            var entity = angular.copy( base );

            _.each( entity.images, function ( image, index ) {
                entity.images[ index ] =  _.pick( image, [ "entity", "caption" ] );
            });

            return entity;
        };

        strips.decorateLoadedImages = function ( entity ) {
            entity.images.forEach( function ( image ) {
                image.loaded = true;
            });
        };

        /**
         * Whether the new entity is edited.
         */
        strips.editingNew = function () {
            return this.editing() && !this.entity.id;
        };

        return strips;
    }]);
