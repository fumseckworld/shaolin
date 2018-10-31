<?php


use Imperium\Connexion\Connect;

require_once  'vendor/autoload.php';


try {

    $mysql = instance(Connect::MYSQL,'root','zen',"root",5,'dump','imperium');
} catch (Exception $e) {
    d($e->getMessage());
}

try {
    $pgsql = instance(Connect::POSTGRESQL, 'postgres', 'zen', "postgres", 5, 'dump', 'imperium');
} catch (Exception $e) {
    d($e->getMessage());
}

try {
    $sqlite = instance(Connect::SQLITE, '', 'zen.sqlite3', "", 5, 'dump', 'imperium');
} catch (Exception $e) {
    d($e->getMessage());
}




