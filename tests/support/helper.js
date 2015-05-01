define({
    getAppUrl: function ( path ) {
        return ( process.env.TRAVIS ? "http://localhost:9090/" : "http://comiccms.dev/" ) + path;
    }
});
