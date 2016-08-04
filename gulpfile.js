/**
 * Created by kmasteryc on 04/08/2016.
 */
var gulp = require('gulp');
var gulpSass = require('gulp-sass');

var srcDir = './pre-compile/scss/';
var desDir = './public/assets/css';

gulp.task('compile-scss', function(){
   return gulp.src(srcDir+"*.scss")
       .pipe(gulpSass({outputStyle:"compressed"}))
       .pipe(gulp.dest(desDir));
});

gulp.task('watch', function(){
   gulp.watch(srcDir+"**/*.scss",['compile-scss']);
});