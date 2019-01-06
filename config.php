<?php


use Imperium\Connexion\Connect;

require_once  'vendor/autoload.php';


try {

    $mysql =  apps(Connect::MYSQL,'root','zen',"root",'localhost','dump','imperium','views',['phinxlog'],[]);
} catch (Exception $e) {
    d($e->getMessage());
}

try {
    $pgsql = apps(Connect::POSTGRESQL, 'postgres', 'zen', "postgres",'localhost' , 'dump', 'imperium','views',['phinxlog'],[]);
} catch (Exception $e) {
    d($e->getMessage());
}

try {
    $sqlite = apps(Connect::SQLITE, '', 'zen.sqlite3', "",'localhost', 'dump', 'imperium','views',['phinxlog','sqlite_sequence'],[]);
} catch (Exception $e) {
    d($e->getMessage());
}
