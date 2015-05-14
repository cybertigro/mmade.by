'use strict';

var gulp = require('gulp'),
    watch = require('gulp-watch'),
    prefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    cssmin = require('gulp-minify-css'),
    sass = require('gulp-sass'),
    gulpif = require('gulp-if'),
    sourcemaps = require('gulp-sourcemaps'),
    spritesmith = require('gulp.spritesmith'),
    rigger = require('gulp-rigger'),
    rimraf = require('rimraf'),
    browserSync = require("browser-sync"),
    reload = browserSync.reload;

var path = {
    build: {
        // html: 'build/',
        js: 'js/',
        css: 'css/',
        img: 'build/img/'
    },
    sprite: {
      css: 'src/style/partials/resources/'
    },
    src: {
        // html: 'src/*.html',
        // email: 'src/index-mail.html',
        js: 'src/js/main.js',
        style: 'src/style/main.scss',
        editorStyle: 'src/style/editor/editor.scss',
        img: 'src/img/**/*.*',
        favicon: 'src/favicon/*.*'
    },
    watch: {
        js: 'src/js/**/*.js',
        style: 'src/style/**/*.scss',
        img: 'src/img/**/*.*',
        favicon: 'src/favicon/*.*'
    },
    clean: './build'
};

var config = {
    server: {
        baseDir: "./build"
    },
    tunnel: false,
    host: 'localhost',
    port: 9000,
    logPrefix: "ulej-FE"
};

var isDev  = false;
var isProd = true;

gulp.task('js:build', function () {
    gulp.src(path.src.js)
        .pipe(rigger())
        .pipe(gulpif(isDev, sourcemaps.init()))
        .pipe(gulpif(isProd, uglify()))
        .pipe(gulpif(isDev, sourcemaps.write()))
        .pipe(gulp.dest(path.build.js))
        .pipe(reload({stream: true}));
});

gulp.task('img:build', function () {
  // var spriteData = gulp.src(path.src.img)
  //   .pipe(spritesmith({
  //       imgName: 'icons.png',
  //       cssName: 'sprite.css'
  //     }));
  //   spriteData.img.pipe(gulp.dest(path.build.img));
  //   spriteData.css.pipe(gulp.dest(path.sprite.css));
  //   gulp.src(path.src.img)
  //       .pipe(gulp.dest(path.build.img));
});

gulp.task('style:build', function () {
    gulp.src(path.src.style)
        .pipe(gulpif(isDev, sourcemaps.init()))
        .pipe(sass())
        .pipe(prefixer({ browsers: ['> 1%','last 4 versions', 'Firefox >= 20', 'Firefox < 20', 'IE 9', 'Opera 12.1'], cascade: false }))
        .pipe(gulpif(isProd, cssmin()))
        .pipe(gulpif(isDev, sourcemaps.write()))
        .pipe(gulp.dest(path.build.css))
        .pipe(reload({stream: true}));
    gulp.src(path.src.editorStyle)
        .pipe(sass())
        .pipe(prefixer({ browsers: ['> 1%','last 4 versions', 'Firefox >= 20', 'Firefox < 20', 'IE 9', 'Opera 12.1'], cascade: false }))
        .pipe(gulpif(isProd, cssmin()))
        .pipe(gulp.dest(path.build.css))
        .pipe(reload({stream: true}));
});

gulp.task('favicon:build', function() {
    gulp.src(path.src.favicon)
        .pipe(gulp.dest(path.build.html));
});

gulp.task('build', [
    'js:build',
    // 'img:build',
    'style:build'
    // , 'favicon:build'
]);

gulp.task('watch', function(){
    watch([path.watch.style], function(event, cb) {
        gulp.start('style:build');
    });
    watch([path.watch.js], function(event, cb) {
        gulp.start('js:build');
    });
    watch([path.watch.favicon], function(event, cb) {
        gulp.start('favicon:build');
    });
    // watch([path.watch.img], function(event, cb) {
    //     gulp.start('img:build');
    // });
});

gulp.task('webserver', function () {
    browserSync(config);
});

gulp.task('clean', function (cb) {
    rimraf(path.clean, cb);
});

// gulp.task('openbrowser', function() {
//     opn( 'http://' + server.host + ':' + server.port + '/build' );
// });

// gulp.task('default', ['build', 'webserver', 'watch']);
gulp.task('default', ['build', 'watch']);
// gulp.task('default', ['email:build', 'webserver', 'watch']);