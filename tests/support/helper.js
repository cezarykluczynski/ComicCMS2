define({
    getAppUrl: function ( path ) {
        return ( process.env.TRAVIS ? "http://localhost:9000/" : "http://comiccms.dev/" ) + path;
    }
});
