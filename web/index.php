<?php
	
	chdir(dirname(__DIR__));
	use App\Models\Users;
	require_once 'vendor/autoload.php';
	
	d(Users::by('5'));