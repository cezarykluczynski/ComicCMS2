define({
    getAppUrl: function ( path ) {
        return ( process.env.TRAVIS ? "http://127.0.0.1:9090/" : "http://comiccms.dev/" ) + path;
    }
});
