<?php




require_once '../vendor/autoload.php';

whoops();
$app = instance(\Imperium\Connexion\Connect::POSTGRESQL,'postgres','zen','postgres',5,'dump','base');


echo $app->form()->validate()->start('/',id(),'','a')->submit('azad','btn',id())->get();
d($app->show_databases(),$app->show_users(),$app->show_tables(),$app);