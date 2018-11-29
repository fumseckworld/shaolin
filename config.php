<?php


use Imperium\Connexion\Connect;

require_once  'vendor/autoload.php';


try {

    $mysql = instance(Connect::MYSQL,'root','zen',"root",'localhost',5,'dump','imperium',['phinxlog'],[]);
} catch (Exception $e) {
    d($e->getMessage());
}

try {
    $pgsql = instance(Connect::POSTGRESQL, 'postgres', 'zen', "postgres",'localhost' ,5, 'dump', 'imperium',['phinxlog'],[]);
} catch (Exception $e) {
    d($e->getMessage());
}

try {
    $sqlite = instance(Connect::SQLITE, '', 'zen.sqlite3', "",'localhost', 5, 'dump', 'imperium',['phinxlog','sqlite_sequence'],[]);
} catch (Exception $e) {
    d($e->getMessage());
}
