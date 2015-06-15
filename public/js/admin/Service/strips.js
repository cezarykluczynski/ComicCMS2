admin
    .factory( "strips", [ "$http", "$rootScope", function( $http, $rootScope ) {
        var strips = {};

        strips.list = [];
        strips.editing = false;
        strips.entity = {};

        strips.refresh = function ( data ) {
            this.list = data.list;
        };

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

        strips.edit = function( entity ) {
            this.editing = true;
        };

        strips.cancelEdit = function () {
            this.editing = false;
        };

        strips.editingNew = function () {
            return !this.entity.id;
        };

        return strips;
    }]);
