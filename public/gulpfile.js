'use strict';

var gulp = require('gulp'),
    gutil = require('gulp-util'),
    compass = require('gulp-compass'),
    livereload = require('gulp-livereload'),
    uglify = require('gulp-uglify');

gulp.task('twig', function() {
    return gulp.src('../app/templates/*.twig')
        .pipe(livereload());
});

gulp.task('compass', function() {
    return gulp.src('./sass/**/*.{sass,scss}')
        .pipe(compass({
            config_file: './config.rb',
            css: 'css',
            sass: 'sass'
        }).on('error', function(error) {
            gutil.log(error);
            this.emit('end');
        }))
        .pipe(gulp.dest('css'))
        .pipe(livereload());
});

gulp.task('compress', function() {
    return gulp.src('./js/*.js')
        .pipe(uglify().on('error', function(error) {
            gutil.log(error);
            this.emit('end');
        }))
        .pipe(gulp.dest('./js/dist'))
        .pipe(livereload());
});

gulp.task('watch', function () {
    livereload.listen();

    // Watch Twig files
    gulp.watch('../app/templates/*.twig', ['twig']);

    // Watch SASS and SCSS files
    gulp.watch('./sass/**/*.{sass,scss}', ['compass']);

    // Watch JS files
    gulp.watch('./js/*.js', ['compress']);
});

gulp.task('default', ['watch']);