<?php


use Imperium\Session\Session;

require_once '../vendor/autoload.php';
whoops();


$session = new Session();

$session->set('welcome')->set('Willy')->set('failure');

$flash = new \Imperium\Flash\Flash($session);

$flash->success('Linux is better');$flash->failure('windows was found');
d($session->get(2),$session->remove(2),$session->all(),$flash->get('success'),$flash->get(\Imperium\Flash\Flash::FAILURE_KEY),$flash->get(\Imperium\Flash\Flash::FAILURE_KEY));

$router = new \Imperium\Router\Router(get('url','/'),'Testing');

$router->add('/','Post@trans','a',GET);
$app = new \Imperium\App\Application($router,'../po','fr','messages','.',[]);

echo $app->run();





