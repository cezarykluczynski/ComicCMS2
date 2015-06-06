admin
    .factory( "comics", [ "$http", function( $http ) {
        var comics = {};

        comics.list = [];
        comics.length = 0;

        comics.refresh = function ( data ) {
            this.list = data.list;
        };

        comics.getLatest = function () {
            return this.list.sort(function ( a, b ) {
                return a.id > b.id;
            }).slice( -1 ).pop();
        };

        comics.loadComics = function () {
            this.loaded = false;
            $http.get( "/rest/comic" ).then( function ( response ) {
                comics.refresh( response.data );
                comics.loaded = true;
            });
        };

        comics.editingNew = function () {
            return !comics.entity.id;
        };

        comics.loadComics();

        return comics;
    }]);
