module.exports = function(grunt) {
    
    let package = grunt.file.readJSON('package.json');

    let version = package.version;

    let apply_version = function (content) { return content.replace(/%ver%/g, version) };

    grunt.initConfig({

        pkg : package,

        readme : {
            'head' : grunt.file.read('HEAD.md'),
            'about': grunt.file.read('ABOUT.md'),
            'readme': grunt.file.read('README.md'),
            'changelog': grunt.file.read('CHANGELOG.md'),
            'upgrade': grunt.file.read('UPGRADE_NOTICE.md'),
        },

        copy:{
            build: {
                expand: true,
                cwd: 'src',
                src: '**',
                dest: 'build/',
                options : {
                    process : apply_version
                }
            },
            dist: {
                expand: true,
                cwd: 'build',
                src: '**',
                dest: 'dist/'
            }
        },

        concat: {
            readme : {
                src: ['HEAD.md'],

                dest: 'build/readme.txt',
                options: {
                    process: true,
                    separator : "\n\n"
                }
            }
        },

        clean : {
            build : ['build'],
            dist : ['dist/*']
        }

    });

    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');

    grunt.registerTask('build', ['clean:build', 'copy:build', 'concat:readme']);
    grunt.registerTask('dist', ['clean:dist', 'copy:dist', 'clean:build']);



    };