const {src,dest,series,parallel,watch} = require('gulp');
const SASS = require('gulp-sass');
const CONCAT = require('gulp-concat');

function js()
{
   return src(['js/jquery-3.3.1.min.js','js/popper.min.js','js/bootstrap.min.js'])
   .pipe(CONCAT('app.js'))
    .pipe(dest('../../web/js'))
    
}

function sass()
{
  return  src('sass/*scss')
    .pipe(SASS())
    .pipe(CONCAT('app.css'))
    .pipe(dest('../../web/css'));
}

function w()
{
   watch('sass/*.scss',sass);
   watch('js/*.js',js);
}

module.exports = {
   default: series(parallel(sass,js)),
   watch : w
}
