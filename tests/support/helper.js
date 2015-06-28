define( [
    "intern/dojo/node!child_process",
    "intern/dojo/node!path"
], function( child_process, path ) {
    return {
        /** Additional randomness. */
        randomStringCount: 0,

        /**
         * Whether tests run locally or on Travis.
         */
        isLocal: function () {
            return ! process.env.TRAVIS;
        },

        /**
         * Return domain for tested application. Localhost for Travis, comiccms.dev for local.
         *
         * @return {string}
         */
        getDomain: function () {
            return this.isLocal() ? "comiccms.dev" : "localhost";
        },

        /**
         * Return a full URL for tested application.
         *
         * @param {string} [path] Relative path.
         */
        getAppUrl: function ( path ) {
            return "http://" + this.getDomain() + "/" + ( path || "" );
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

            return this.exec( "php public/index.php get-admin-session-id " + credentials.email + " " +
                credentials.password );
        },

        /**
         * Executes command.
         *
         * @return {string} Execution result.
         */
        exec: function ( command ) {
            return child_process.execSync( command, {
                encoding: "utf-8"
            });
        },

        /**
         * Creates entity with a given data.
         *
         * @param {string} entity FQN of entity class.
         * @param {Object} data   Data to hydrate entity with.
         */
        createEntity: function ( entity, data ) {
            data = JSON.stringify( data ).replace( /\"/gi, "\\\"" );
            return this.exec( "php public/index.php create-entity " + entity + " " + data );
        },

        /**
         * Removes entity, based on criteria.
         *
         * @param {string} entity   FQN of entity class.
         * @param {Object} criteria Criteria to use when searching for entity.
         */
        removeEntity: function ( entity, criteria ) {
            criteria = JSON.stringify( criteria ).replace( /\"/gi, "\\\"" );
            return this.exec( "php public/index.php remove-entity " + entity + " " + criteria );
        },

        /**
         * Loads fixtures by class name.
         *
         * @param {string} name FQN of fixture class.
         */
        loadFixtures: function ( name ) {
            return JSON.parse( this.exec( "php public/index.php load-fixtures " + name ) );
        },

        /**
         * Unload given fixtures.
         *
         * @param {Object} data ID's of fixtures to remove.
         */
        unloadFixtures: function ( data ) {
            data = JSON.stringify( data ).replace( /\"/gi, "\\\"" );
            return this.exec( "php public/index.php unload-fixtures " + data );
        },

        /**
         * Returns valid Selenium cookie for an logged in admin
         *
         * @return {object}
         */
        getValidAdminCookie: function () {
            /** @type {string} Cookie validity string, a week from now. */
            var nextWeek = new Date( (new Date).getTime() + 7 * 24 * 60 * 60 * 1000 ).toUTCString();

            return {
                expiry: nextWeek,
                name: "PHPSESSID",
                value: this.getAdminSessionId(),
                path: "/",
                domain: null
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
                .maximizeWindow()
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
        },

        /**
         * Generates and returns unique string, one that is highly unlikely to repeat in a single test,
         * based on microtime and internal
         *
         * @return {string} Unique string.
         */
        getUniqueString: function () {
            this.randomStringCount++;

            return "uniqueString" + (new Date()).getTime() + this.randomStringCount;
        },

        /**
         * Returns shared timeout for those action that triggers AJAX requests.
         *
         * @return {int}
         */
        getTimeoutForAjaxRequests: function() {
            return 15000;
        },

        /**
         * Returns shared timeout for those action that triggers AJAX requests.
         *
         * @return {int}
         */
        getTimeoutForPageAction: function() {
            return 10000;
        },

        /**
         * Returns absolute path, given a relative fixture path.
         *
         * @param  {string} relativePath Relative path.
         * @return {string}              Absolute path.
         */
        getFixturePath: function ( relativePath ) {
            return process.cwd() + path.sep + [ "tests", "support", "fixtures" ]
                .concat( relativePath.split( "/" ) )
                .join( path.sep );
        }
    };
});
