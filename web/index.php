<?php

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

(new \Imperium\Http\Routing\Route(\App\Controllers\WelcomeController::class, 'run'))->exec();
