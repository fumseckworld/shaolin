<?php
	
	chdir(dirname(__DIR__));
	use App\Models\Users;
	require_once 'vendor/autoload.php';
	app()->session()->put('a','c');
	app()->session()->put('c','e');
	app()->session()->put('b','f');
	d(app()->session()->all(),app()->session()->get('a'),app()->session()->has('b'),app()->session()->def('del','del'),app()->session()->remove('del'),app()->session()->clear(),app()->session()->all());