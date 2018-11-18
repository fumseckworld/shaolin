<?php

use Imperium\Connexion\Connect;

 
require_once '../vendor/autoload.php';
echo bootswatch('lumen');
echo fontAwesome();
whoops();
$app = instance(Connect::MYSQL,'root','zen','root',Connect::LOCALHOST,5,'dump','base');

echo '<div class="container mt-5">';
echo $app->form(true)->margin(5)->padding(5)->start('/','id','are you sure')->row()->input('text','name','username',fa('fas','fa-user'),'name will be used','name required')->input('number','age','age','','age will be used','age required')->end_row_and_new()->select('base',$app->show_databases(),'base will be used','base is require')->select('user',$app->show_users(),'user will be use','danger')->end_row_and_new()->textarea('name','description',10,10,'desc will be used','desc are required')->end_row_and_new()->submit('submit','btn-danger btn','id')->end_row()->get();

echo '</div>';