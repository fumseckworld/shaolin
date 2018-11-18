<?php

 header('Content-Type: application/json');
require_once '../vendor/autoload.php';

whoops();
$app = instance(\Imperium\Connexion\Connect::POSTGRESQL,'postgres','zen','postgres',5,'dump','base');

echo $app->json()->set_name('app.json')->add($app->show_users(),'users')->add($app->show_databases(),'bases')->add($app->show_tables(),'table')->add($app->all(),'records')->encode();