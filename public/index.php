<?php




require_once '../vendor/autoload.php';

echo css_loader('lily/lily-dark.css');

whoops();
$app = instance(\Imperium\Connexion\Connect::MYSQL,'root','zen','root',5,'dump','doctors');

echo fontAwesome();

echo  html('h1','Register','text-center text-uppercase mt-5');

echo  html('div',register('','','','username','success','fail','email','success','fail','pass','success','fail','confirm','create the account','create',true,['en' => 'english','fr' =>'french'],'choose','success','fail','choose','success','fail'),'container mt-5');
echo  html('h1','query','text-center text-uppercase mt-5');
_html(false,html('div',query_view('/',$app->model(),$app->tables(),'create','update','create record','update record','doctors','Type the expected value','superior','superior or equal','inferior','inferior or equal','different','equal','like','select','remove','update','submit','btn btn-outline-primary','success','not found','is empty','Define the where column','Select a condition','Define the operation','Define the column order','Reset the form'),'mt-5 container'));

