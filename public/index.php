<?php




require_once '../vendor/autoload.php';

whoops();
$app = instance(\Imperium\Connexion\Connect::POSTGRESQL,'postgres','zen','postgres',5,'dump','base');

d($app->collations());