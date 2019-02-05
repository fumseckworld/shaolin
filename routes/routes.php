<?php


use GuzzleHttp\Psr7\ServerRequest;
use Imperium\Router\Router;

$router = new Router(ServerRequest::fromGlobals());

$router->add('/remove/:table/:id','Controller@remove','remove',GET,true,['table','id'],STRING,NUMERIC);
$router->add('/','Controller@show','home',GET);
$router->add('/salut/:id','Controller@show','salut',GET);

$router->add('/query','Controller@execute','query',POST);
$router->add('/query','Controller@execute','query',GET);
$router->add('/create','Controller@create','create',POST);
$router->add('/update','Controller@update','update',POST);
