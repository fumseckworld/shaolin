<?php


use Imperium\App;

require_once '../vendor/autoload.php';
require_once '../routes/routes.php';
whoops();




App::init()->run($router);




