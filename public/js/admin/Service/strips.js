admin
    .factory( "strips", [ "$http", function( $http ) {
        var strips = {};

        strips.list = [];
        strips.length = 0;

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

        return strips;
    }]);
