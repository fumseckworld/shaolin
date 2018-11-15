<?php




require_once '../vendor/autoload.php';

echo css_loader('lily/lily-dark.css');

whoops();
$app = instance(\Imperium\Connexion\Connect::MYSQL,'root','zen','root',5,'dump','base');


d($app->show_databases(),$app->show_users(),$app->show_tables());