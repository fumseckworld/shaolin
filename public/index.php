<?php

 
require_once '../vendor/autoload.php';
echo bootswatch('lumen');
echo fontAwesome();
whoops();
$app = instance(\Imperium\Connexion\Connect::POSTGRESQL,'postgres','zen','postgres',5,'dump','base');

echo '<div class="container">';
echo $app->form(true)->start('/','id','was-validated mt-5','are you sure')->row()->input('text','name','username',fa('fas','fa-user'),'name will be used','name required')->input('number','age','age','','age will be used','age required')->end_row_and_new()->submit('submit','btn-danger btn','id')->end_row()->get();

echo '</div>';