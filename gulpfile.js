const {src,dest,series,parallel,watch} = require('gulp');
const SASS = require('gulp-sass');
const CONCAT = require('gulp-concat');

function js()
{
   return src(['app/Assets/js/jquery-3.3.1.min.js','app/Assets/js/popper.min.js','app/Assets/js/bootstrap.min.js'],{sourceMap: true})
   .pipe(CONCAT('app.js'))
    .pipe(dest('web/js'),{sourceMap:'.'})
    
}

function sass()
{
  return  src('app/Assets/sass/*scss',{sourceMap:true})
    .pipe(SASS())
    .pipe(CONCAT('app.css'))
    .pipe(dest('web/css'),{sourceMap:'.'});
}

function w()
{
   watch('app/Assets/sass/*.scss',sass);
   watch('app/Assets/js/*.js',js);
}

module.exports = {
   default: series(parallel(sass,js)),
   watch : w
};
