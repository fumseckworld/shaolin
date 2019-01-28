<?php


use GuzzleHttp\Psr7\ServerRequest;
use Imperium\Router\Router;

$router = new Router(ServerRequest::fromGlobals(),'Testing');

$router->add('/remove/:table/:id','Controller@remove','remove',GET,true,['table','id'],STRING,NUMERIC);
$router->add('/','Controller@show','home',GET);
$router->add('/salut/:id','controller@show','salut',GET);