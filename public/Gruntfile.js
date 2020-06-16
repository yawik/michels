module.exports = function(grunt) {
    var targetDir = grunt.config.get('targetDir');
    var nodeModulesPath = grunt.config.get('nodeModulesPath');

    grunt.config.merge({
        less: {
            michels: {
                options: {
                    compress: false,
                    modifyVars: {
                        "fa-font-path": "/dist/fonts",
                        "flag-icon-css-path": "/dist/flags"
                    }
                },
                files: [
                    {
                        src: [
                            "./node_modules/select2/dist/css/select2.min.css",
                            "./node_modules/pnotify/dist/pnotify.css",
                            "./node_modules/pnotify/dist/pnotify.buttons.css",
                            "./node_modules/bootsrap3-dialog/dist/css/bootstrap-dialog.css",
                            targetDir+"/modules/Michels/less/Michels.less"
                        ],
                        dest: targetDir+"/modules/Michels/dist/Michels.css"
                    }
                ]
            },
            jobs: {
                files: [
                    {
                        src: "./view/jobs/templates/less/job.less",
                        dest: "./view/jobs/templates/job.css"
                    }
                ]
            }
        },
        cssmin: {
            michels: {
                files: [
                    {
                        dest: targetDir+'/modules/Michels/dist/Michels.min.css',
                        src: targetDir+'/modules/Michels/dist/Michels.css'
                    }
                ]
            }
        }
    });

    grunt.registerTask('yawik:michels',['copy','less','less:job','concat','uglify','cssmin']);
};