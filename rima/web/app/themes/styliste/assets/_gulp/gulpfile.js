/* Modules
------------------------------------- */
var gulp          = require('gulp'),
    // Tools
    watch         = require('gulp-watch'),
    rename        = require("gulp-rename"),

    // Styles
    sass          = require('gulp-sass'),
    autoprefixer  = require('gulp-autoprefixer'),
    minifyCSS     = require('gulp-minify-css'),

    // Scripts
    uglify        = require('gulp-uglify'),
    rjs           = require('gulp-requirejs-optimize'),

    // BrowserSync
    browserSync   = require('browser-sync'),

    // Prevent watch from crashing on errors
    plumber       = require('gulp-plumber'),

    // get external configs
    rjs_paths     = require('./rjs/rjs-paths.json'),
    rjs_shims     = require('./rjs/rjs-shims.json'),

    gulp_src      = gulp.src;

    // Debug (optional)
    // debug         = require('gulp-debug');

gulp.src = function() {
  return gulp_src.apply(gulp, arguments)
    .pipe(plumber(function(error) {
      console.log(error);
      this.emit('end');
    })
  );
};


/* Paths
------------------------------------- */
var paths = {
  sass: '../sass/',
  css: '../css/',
  js: '../js/'
}



/* Tasks
------------------------------------- */
// RequireJS
gulp.task('requirejs', function() {
    gulp.src(paths.js + 'main.js')
    .pipe(rjs({
        baseUrl: paths.js,
        optimize: 'none',
        name: 'main',
        out: 'application.js',
        deps: ['almond'],
        paths: rjs_paths,
        shim: rjs_shims
    }))
    .pipe(gulp.dest(paths.js))
    .pipe(browserSync.reload({stream: true}));
});


// BrowserSync Task
gulp.task('browsersync', function() {
  browserSync({
    // Specify proxy or port setting to use BrowserSync
    proxy: "localhost:8888"
    // port: 8888
  });
});


// Sass Task
gulp.task('sass',  function () {
  gulp.src(paths.sass + 'application.sass')
    .pipe(sass({indentedSyntax: true}))
    .pipe(gulp.dest(paths.css));
});

// Autoprefixer Task
gulp.task('autoprefix', function () {
  gulp.src(paths.css + 'application.css')
    .pipe(autoprefixer({
        browsers: ['> 1%'],
        cascade: false
    }))
    .pipe(gulp.dest(paths.css))
    .pipe(browserSync.reload({stream: true}));
});

// Minify-CSS Task
gulp.task('minify', function() {
  gulp.src([paths.css+'*.css', '!'+paths.css+'*.min.css'])
    .pipe(minifyCSS({keepBreaks:false}))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(paths.css));
  gulp.src(paths.js + 'application.js')
    .pipe(uglify({
        mangle: true,
        preserveComments: 'some'
      }))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(paths.js))

});



/* METHODS
------------------------------------- */

//
//
// Watch Task
gulp.task('watch', ['requirejs'], function () {
    gulp.watch(paths.sass+'**/*.sass', ['sass']);
    gulp.watch(paths.css+'*.css', ['autoprefix']);
    gulp.watch([paths.js+'**/*.js', '!'+paths.js+'application.js'], ['requirejs']);
});

//
//
// EXPORT
gulp.task('export', ['minify']);

//
//
// SYNC
gulp.task('sync', ['browsersync', 'watch']);

//
//
// DEFAULT
gulp.task('default', ['sync']);


