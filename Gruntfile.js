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
        }
    });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['sass']);

};