define({
    getAppUrl: function ( path ) {
        return ( process.env.TRAVIS ? "http://localhost/" : "http://comiccms.dev/" ) + path;
    },

    getAdminCredentials: function () {
        return {
            email: "admin@example.com",
            password: "password"
        };
    }
});
