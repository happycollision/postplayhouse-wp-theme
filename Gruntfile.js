module.exports = function(grunt) {
	var sassFiles = {
		'build/style.css': 'app/scss/style.scss',      
		'build/rtl.css': 'app/scss/rtl.scss',
		'build/css/editor-style.css': 'app/scss/css/editor-style.scss',
		'build/css/ie.css': 'app/scss/css/ie.scss',
		'build/css/calendar.css': 'app/scss/css/calendar.scss'
	};
	
	var uglifyFiles = {
		'build/lib/head.js' : ['app/bower_components/modernizr/modernizr.js'],
		'build/lib/foot.js' : ['app/bower_components/lowFloat/jquery.lowfloat.js', 'app/lib/foot_commands.js'],
		'build/lib/calendar_scripts.js' : ['app/lib/calendar_scripts.js'],
		'build/lib/front_page_scripts.js' : ['app/lib/front_page_scripts.js']
	};

	var deployDir = '/Users/Don/Sites/happycollision/wp-content/themes/postplayhousechild';
	
	grunt.initConfig({
	  compass: {
	  	options: {
	  		sassDir: 'app/scss',
	  		cssDir: 'build',
	  		generatedImagesDir: 'images/generated',
	  		imagesDir: 'images',
	  		javascriptsDir: 'scripts',
	  		fontsDir: 'styles/fonts',
	  		importPath: 'app/bower_components',
	  		httpImagesPath: '/images',
	  		httpGeneratedImagesPath: '/images/generated',
	  		httpFontsPath: '/styles/fonts',
	  		relativeAssets: false,
	  		assetCacheBuster: false,
	  		debugInfo: true
	  	},
	  	dist: {
	  		options: {
		  		specify: ['app/scss/**/*.scss','!app/scss/tests.scss'],
		  		debugInfo: false
	  		},
	  	},
	  	dev: {}
	  },

	  watch: {
	  	compass: {
				// scss/{,*/}*.{scss,sass} : one level down
				// scss/**/*.{scss,sass} : all levels
				files: ['app/scss/**/*.{scss,sass}'], 
				tasks: ['compass:dev', 'concat:testCss', 'copy:deployCss']
			},
			uglify: {
				files: ['app/lib/*.js'], 
				tasks: ['uglify:dev', 'concat:testJs', 'copy:deployJs']
			},
			php: {
				files: ['app/*.php', 'app/extensions/*.php'],
				tasks: ['copy:php', 'copy:deployPhp']
			}
		},
		uglify: {
			dev: {
				options: {
					compress: false, //doesn't seem to work. Ugh.
					mangle: false,
					beautify: true
				},
				files: uglifyFiles
			},
			dist: {
				options: {
					compress: {
						drop_console: true
					}
				},
				files: uglifyFiles
			}
		},
		concat:{
			testCss:{
				files:{
					'.tmp/style.css' : ['build/style.css','build/tests.css'],
					'build/style.css' : ['.tmp/style.css']
				}
			},
			testJs:{
				files: {
					'.tmp/lib/foot.js' : ['lib/foot.js','app/lib/tests.js'],
					'build/lib/foot.js' : ['.tmp/lib/foot.js']
				}
			}
		},
		copy: {
			php: {
				files: [
					{expand: true, flatten: true, src: ['app/*.php'], dest: 'build/'},
					{expand: true, flatten: true, src: ['app/extensions/*.php'], dest: 'build/extensions/'}					
				]
			},
			remaining: {
				files: [{
					expand: true,
					cwd: 'app/',
					src: ['*.ico','images/*'],
					dest: 'build/'					
				}]
			},
			deployAll:{
				files: [
					{expand: true, cwd: 'build/', src: ['**'], dest: deployDir }
				]
			},
			deployCss:{
				files: [
					{expand: true, cwd: 'build/', src: ['*.css','**/*.css'], dest: deployDir }
				]
			},
			deployJs:{
				files: [
					{expand: true, cwd: 'build/', src: ['*.js','**/*.js'], dest: deployDir }
				]
			},
			deployPhp:{
				files: [
					{expand: true, cwd: 'build/', src: ['*.php','**/*.php'], dest: deployDir }
				]
			}
		},
		clean: ['build', '.tmp']
	});

	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-clean');

	grunt.registerTask('default', ['build']);
	grunt.registerTask('dev', ['build', 'copy:deployAll', 'watch']);
	//grunt.registerTask('build', ['compass:dist', 'uglify:dist']);
	grunt.registerTask('build', [
		'clean',
		'compass:dev', 
		'uglify:dev', 
		'concat', 
		'copy:php', 
		'copy:remaining'
	]);
	
	grunt.registerTask('build:dist', [
		'clean',
		'compass:dist', 
		'uglify:dev', 
		'concat', 
		'copy:php', 
		'copy:remaining'
	]);


}