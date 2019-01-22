<?php


use GuzzleHttp\Psr7\ServerRequest;

require_once '../vendor/autoload.php';
whoops();



$router = new \Imperium\Router\Router(ServerRequest::fromGlobals(),'Testing');

$router->add('/','Post@home','a',GET);
$router->add('/salut/:id','Post@show','salut',GET);
$app = new \Imperium\App\Application($router,'../po','fr','messages','../views',[]);

echo $app->run();





