module.exports = function(grunt) {

    require('jit-grunt')(grunt, {});

    grunt.initConfig({
        sass: {
            options: {
                style: 'expanded',
            },
            build: {
                files: {
                   'public/css/tapestry.css': ['assets/sass/build.scss']
                }
            }
        },
        autoprefixer: {
            options: {},
            build: {
                src: ['public/css/tapestry.css']
            }
        },
        uglify: {
            build: {
                files: {
                    'public/js/tapestry.js': [
                        'assets/js/vendor/jquery.js',
                        'assets/js/main.js'
                    ]
                }
            }
        },
        watch: {
            options: {
                livereload: 3003,
            },
            css: {
                files: ['assets/**/*.scss'],
                tasks: ['css']
            },
            js: {
                files: ['assets/**/*.js'],
                tasks: ['js']
            },
            grunt: {
                files: ['gruntfile.js'],
                tasks: ['default']
            } 
        }
    });



    grunt.registerTask('css', ['sass', 'autoprefixer']);
    grunt.registerTask('js', ['uglify']);

    grunt.registerTask('default', ['css', 'js']);

  
};