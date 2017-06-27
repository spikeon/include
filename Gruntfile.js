module.exports = function(grunt) {
    
    let package = grunt.file.readJSON('package.json');

    let version = package.version;

    let filter_php = function (content) {
        return content
            .replace(/%ver%/g, version) // Version Number
            .replace("../framework/dist", 'framework')
    };

    grunt.initConfig({

        pkg : package,

        readme : {
            'about':        grunt.file.read('readme/ABOUT.md'),
            'readme':       grunt.file.read('readme/BODY.md'),
            'changelog':    grunt.file.read('readme/CHANGELOG.md'),
            'upgrade':      grunt.file.read('readme/UPGRADE_NOTICE.md'),
        },

        copy:{
            build: {
                expand: true,
                cwd: 'src',
                src: '**',
                dest: 'build/',
                options : {
                    process : filter_php
                }
            },
            gallery: {
                src: 'gallery*.png',
                dest: 'assets/',
                rename: (dest,src) => {
                    return dest + src.replace('gallery', 'screenshot-');
                }
            },
            thumb: {
                src: ['icon.png'],
                dest: 'thumb.png'
            },

            icon_svg: {
                src: ['icon.svg'],
                dest: 'assets/icon.svg'
            },
            framework : {
                expand: true,
                cwd: 'framework',
                src: ['**'],
                dest: 'build/framework'
            },
            dist: {
                expand: true,
                cwd: 'build',
                src: '**',
                dest: 'dist/'
            }
        },

        concat: {
            readme_wp : {
                src: ['readme/WORDPRESS.md'],

                dest: 'build/readme.txt',
                options: {
                    process: true,
                    separator : "\n\n"
                }
            },
            readme : {
                src: ['readme/README.md'],

                dest: 'README.md',
                options: {
                    process: true,
                    separator : "\n\n"
                }
            }
        },

        clean : {
            init :        ['build', 'README.md'],
            dist :        ['dist/*'],
            assets_pre :  ['assets', 'thumb.png'],
            assets_post : ['icon.png'],
            end:          ['build'],
            prepush:      ['assets']
        },

        "convert-svg-to-png" : {
            icon : {
                options: {
                    size: {w: "500px", h: "500px"},
                },
                files: [
                    {
                        src : 'icon.svg',
                        dest : '.'
                    }
                ]
            }
        },

        image_resize: {

            icon_small: {
                options: {
                    width: 128,
                    height: 128
                },
                files: {
                    'assets/icon-128x128.png': 'icon.png'
                }
            },

            icon_large: {
                options: {
                    width: 256,
                    height: 256
                },
                files: {
                    'assets/icon-256x256.png': 'icon.png'
                }
            }

        }

    });

    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-image-resize');
    grunt.loadNpmTasks('grunt-convert-svg-to-png');

    grunt.registerTask('assets', [
        'clean:assets_pre',
        'copy:gallery',
        'convert-svg-to-png:icon',
        'copy:thumb',
        'image_resize:icon_small',
        'image_resize:icon_large',
        'clean:assets_post'
    ]);
    grunt.registerTask('build', ['clean:init', 'copy:build', 'copy:framework', 'concat:readme', 'concat:readme_wp']);
    grunt.registerTask('dist', ['clean:dist', 'copy:dist', 'clean:end']);
};