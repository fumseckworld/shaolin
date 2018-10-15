<?php




require_once '../vendor/autoload.php';

echo css_loader('lily/lily-dark.css');

whoops();

$app = instance(\Imperium\Connexion\Connect::POSTGRESQL,'postgres','zen','postgres',5,'dump','doctors');

_html(false,html('div',query_view('/',$app->model(),$app->tables(),'create','update','create record','update record','doctors','expected','superior','superior or equal','inferior','inferior or equal','different','equal','like','select','remove','update','submit','btn btn-primary','success','not found','is empty'),'mt-5 container'));
