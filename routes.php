<?php

use Imperium\Route\Route;

require_once 'vendor/autoload.php';

Route::root(function (){echo "homepage";});

Route::get('lorem',function (){},'homepage');

Route::get('echo/:id','Post@echo','echo');
Route::get('show/:id','Post@show','show');

Route::get('hug/:slug',function ($slug){echo $slug;},"salut");
Route::get('arts/:slug-:id',function ($slug,$id){echo "slug $slug id => $id";},"arts")->with('id',Route::NUMERIC)->with('slug',Route::STRING);
