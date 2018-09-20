<?php

use Imperium\Connexion\Connect;
use Imperium\Imperium;

require_once 'vendor/autoload.php';
echo css_loader('lily\lily-dark.css');

$connect = new  Connect(\Imperium\Connexion\Connect::MYSQL,'zen','root','');
$app = new Imperium($connect,'patients');

echo html('div',query_view('index.php',$app->model(),$app->tables(),'create','update','create','update',$app->tables()->get_current_table(),'expected','superior','superior or equal','inferior','inferirt or equal','different','equal','like','SELECT','remove','update','submit','btn btn-outline-primary','remove successfully','record not found','tables is empty'),'container mt-5');