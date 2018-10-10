<?php


use Imperium\Route\Route;

require_once '../vendor/autoload.php';

whoops();

Route::get('app','adz@a');


Route::capture(get('url'))->run();