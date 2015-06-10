define( [ "intern/dojo/node!child_process" ], function( child_process ) {
    return {
        /**
         * Return a full URL for an tested application.
         *
         * @param {string} [path] Relative path.
         */
        getAppUrl: function ( path ) {
            return "http://" + this.getDomain() + "/" + ( path || "" );
        },

        /**
         * Return domain for tested application. Localhost for Travis, comiccms.dev for local.
         *
         * @return {string}
         */
        getDomain: function () {
            return process.env.TRAVIS ? "localhost" : "comiccms.dev";
        },

        /**
         * Returns object containing email and password for test admin.
         *
         * @return {string}
         */
        getAdminCredentials: function () {
            return {
                email: "admin@example.com",
                password: "password"
            };
        },

        /**
         * Returns PHP session ID for admin credendials stored in test helper.
         *
         * @return {Object}
         */
        getAdminSessionId: function () {
            var credentials = this.getAdminCredentials();
            return child_process.execSync( "php public/index.php get-admin-session-id " + credentials.email + " " +
                credentials.password, {
                    encoding: "utf-8"
                }
            );
        },

        /**
         * Returns valid Selenium cookie for an logged in admin
         *
         * @return {object}
         */
        getValidAdminCookie: function () {
            /** @type {string} Cookie validity string, a week from now. */
            var nextWeek = new Date((new Date).getTime() + 7 * 24 * 60 * 60 * 1000).toUTCString();

            return {
                expiry: nextWeek,
                name: "PHPSESSID",
                value: this.getAdminSessionId(),
                path: "/",
                domain: this.getDomain()
            };
        },

        /**
         * Setup for all dashboard tests. It visits main page, sets the right cookie, then opens dashboard.
         *
         * @return {Object}
         */
        getDashboardAuthorizedAsAdmin: function( context ) {
            var mainPage = this.getAppUrl( "" );
            var dashboard = this.getAppUrl( "admin" );

            return context
                .remote
                .get( mainPage )
                .clearCookies()
                .setCookie( this.getValidAdminCookie() )
                .get( dashboard );
        },

        /**
         * Cleanup function for dashboard tests. Destroys all cookies.
         *
         * @return {Object}
         */
        cleanupDashboardTest: function ( context ) {
            return context
                .clearCookies();
        }
    };
});
