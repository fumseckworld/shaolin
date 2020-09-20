const browsersync = require("browser-sync");
const sass = require("gulp-sass");
const concat = require('gulp-concat');
const gulp = require("gulp");
const phpConnect = require('gulp-connect-php');


function style()
{
    // Where should gulp look for the sass files?
    // My .sass files are stored in the styles folder
    // (If you want to use scss files, simply look for *.scss files instead)
    return (
        gulp
            .src("design/sass/*.scss")
            .pipe(sass({outputStyle: 'compressed'}))
            .pipe(concat("app.css"))
            .on("error", sass.logError)
            .pipe(gulp.dest("web/css"))
    );
}


//Php connect
function connectsync()
{
    phpConnect.server({
        // a standalone PHP server that browsersync connects to via proxy
        port: 3000,
        keepalive: true,
        base: "app"
    }, function () {
        browsersync({
            proxy: 'nol.ji'
        });
    });
}

// BrowserSync Reload
function browserSyncReload(done)
{
    browsersync.reload();
    done();
}


// Watch files
function watchFiles()
{
    gulp.watch('design/sass/*.scss',style);
    gulp.watch("app/**/*.php", gulp.series(browserSyncReload));
    gulp.watch("web/css/*.css", gulp.series(browserSyncReload));
    gulp.watch("web/js/*.js", gulp.series(browserSyncReload));
}

const watch = gulp.parallel([watchFiles, connectsync]);

exports.default = watch;