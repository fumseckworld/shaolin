<?php

use Imperium\Connexion\Connect;
use Imperium\Imperium;

require_once 'vendor/autoload.php';


$connect = new  Connect(\Imperium\Connexion\Connect::MYSQL,'zen','root','');
$app = new Imperium($connect,'doctors');

echo collection($app->all())->print(true);