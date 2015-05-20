admin
    .factory( "comics", [ "$http", function( $http ) {
        var comics = {};

        comics.list = {};
        comics.length = 0;

        comics.merge = function ( data ) {
            data.list.forEach(function ( comic ) {
                comics.add( comic );
            });
        };

        comics.add = function ( comic ) {
            comics.list[ comic.id ] = comic;
            comics.length = Object.keys( comics.list ).length;
        }

        comics.getLatest = function () {
            return this.list[ Object.keys( this.list ).sort().pop() ];
        }

        comics.loadComics = function () {
            this.loaded = false;
            $http.get( "/rest/comic" ).then( function ( response ) {
                comics.merge( response.data );
                comics.loaded = true;
            });
        };

        comics.loadComics();

        return comics;
    }]);
