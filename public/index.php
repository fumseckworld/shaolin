<?php


require_once '../vendor/autoload.php';
whoops();

$router = new \Imperium\Router\Router(get('url','/'),'Testing');
$router->add('/','Post@trans','a',GET);
$app = new \Imperium\App\Application($router,'../po','fr','messages','.',[]);

echo $app->run();





