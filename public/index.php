<?php

use Imperium\Router\Router;

require_once '../vendor/autoload.php';

whoops();


(new Router(get('url'),'Testing'))
    ->add('/', function (){echo "homepage";},'homepage',Router::METHOD_GET)
    ->add('lorem',function (){echo 'lorem';},'lorem',Router::METHOD_GET)
    ->add('show/:id','Post@show','laorem',Router::METHOD_GET)
    ->run();





