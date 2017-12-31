"use strict";

var gulp = require('gulp');
var gutil = require('gulp-util');
var browserify = require('gulp-browserify');
var compass = require('gulp-compass');
var connect = require('gulp-connect');
var gulpif = require('gulp-if');
var uglify = require('gulp-uglify');
// var minifyHTML = require('gulp-minify-html');
var concat = require('gulp-concat');
var maps = require('gulp-sourcemaps');
var gpath = require('path');
var livereload = require('gulp-livereload');

var env;
var	jsSources;
var sassSources;
var outputDir;
var sassStyle;

env = 'development';

if (env === 'development') {
	outputDir = '';
	sassStyle = 'expanded';
} else {
	outputDir = 'production';
	sassStyle = 'compressed';
}

jsSources = [
	'js/navigation.js',
	'js/skip-link-focus-fix.js',
	'js/script.js'
];

sassSources = ['sass/style.scss'];

// JAVASCRIPT
gulp.task('js', function(){
	gulp.src(jsSources)
		.pipe(concat('app.js'))
		.on('error', gutil.log)
		.pipe(gulpif(env === 'production', uglify()))
		.pipe(gulp.dest(outputDir + 'js'))
		.pipe(livereload())
}); //js task

// COMPASS
gulp.task('compass', function(){
	gulp.src(sassSources)
		.pipe(compass({
			sourcemap: true,
			sass: 'sass',
			css: outputDir + '',
			image: outputDir + 'images',
			style: sassStyle,
			comments: true,
			require: ['susy', 'breakpoint']
		})
		.on('error', gutil.log))
		.pipe(gulp.dest(outputDir + ''))
		.pipe(livereload())
}); //compass task

// WATCH
gulp.task('watch', function(){
	livereload.listen();
	gulp.watch(jsSources, ['js']);
	gulp.watch(['sass/*.scss', 'sass/*/*.scss'], ['compass']);
});

//DEFAULT
gulp.task('default', ['watch', 'js', 'compass']);
