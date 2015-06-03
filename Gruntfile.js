module.exports = function(grunt) {
    grunt.initConfig({
        sass: {
            dist: {
                options: {
                    style: 'expanded',
                    sourcemap: 'none'
                },
                files: {
                    'public/css/admin.css': 'public/css/admin.sass',
                    'public/css/application.css': 'public/css/application.sass',
                }
            }
        },
        watch: {
            sass: {
                files: 'public/css/**/*.sass',
                tasks: ['sass'],
            }
        },
        /** Intern config for functional tests. */
        intern: {
            /** Local functional tests config. */
            runner: {
                options: {
                    runType: "runner",
                    config: "tests/intern",
                    reporters: [ "Combined" ]
                }
            },
            /** CI functional tests config. */
            "runner-ci": {
                options: {
                    runType: "runner",
                    config: "tests/intern-ci",
                    reporters: []
                }
            }
        },
        coveralls: {
            all: {
                src: "lcov.info",
                force: true
            }
        }
    });

    /** Load built-in grunt task from intern. */
    grunt.loadNpmTasks( "intern" );

    /** Load task for uploading coverage reports to coveralls.io. */
    grunt.loadNpmTasks( "grunt-coveralls" );

    /** SASS related tasks: SASS compiler and generic watcher. */
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');

    /** Main task for local testing. */
    grunt.registerTask( "test", "Funcional tests ran locally.", function () {
        grunt.task.run( "delete-coverage-reports" );
        grunt.task.run( "intern:runner" );
    });

    /** Main task for Travis. */
    grunt.registerTask( "test-ci", "Functional tests ran on Travis.", function () {
        /** Don't run this task outside of CI! */
        if ( ! process.env.TRAVIS ) {
            console.error( "This task should only be run by Travis." );

            return;
        }

        grunt.task.run( "intern:runner-ci" );
    });

    /** Aliases for both sub-tasks. */
    grunt.registerTask( "test:client", "intern:client" );
    grunt.registerTask( "test:runner", "intern:runner" );

    /** Make "test" task the default task for "grunt" command. */
    grunt.registerTask( "default", [ "test" ] );

    /** Deletes coverage-final.json, so combined coverage will be accurate. */
    grunt.registerTask( "delete-coverage-reports", "Deletes coverage reports from root directory.",
    function () {
        try {
            require( "fs" ).unlinkSync( "coverage-final.json" );
        } catch ( i ) {}
        try {
            require( "fs" ).unlinkSync( "lcov.info" );
        } catch ( i ) {}
    });

};