admin
    .factory( "strips", [ "$http", "$rootScope", function( $http, $rootScope ) {
        var strips = {};

        strips.list = [];
        strips._editing = false;
        strips.entity = {};

        /**
         * Reload entity list.
         */
        strips.refresh = function ( data ) {
            this.list = data.list;
        };

        strips.editing = function ( editing ) {
            if ( undefined === editing ) {
                return this._editing;
            }

            this._editing = editing;
        }

        strips.setComicId = function ( comicId ) {
            this.comicId = comicId;
            this.loadStrips();
        };

        strips.loadStrips = function () {
            this.loaded = false;
            $http.get( "/rest/comic/" + this.comicId + "/strip" ).then( function ( response ) {
                strips.refresh( response.data );
                strips.loaded = true;
            });
        };

        $rootScope.$on( "reloadStrips", function () {
            strips.loadStrips();
        });

        strips.new = function () {
            var entity = {
                id: null,
                title: ""
            };

            this.edit( entity );
        }

        strips.edit = function( entity ) {
            this.entity = entity;
            this.editing( true );
        };

        /**
         * Cancel edit.
         */
        strips.cancelEdit = function () {
            this.editing( false );
        };

        /**
         * Whether the new entity is edited.
         */
        strips.editingNew = function () {
            return this.editing() && !this.entity.id;
        };

        return strips;
    }]);
