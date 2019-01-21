<?php


use Imperium\Connexion\Connect;

require_once  'vendor/autoload.php';

\Dotenv\Dotenv::create('.')->load();

try {

    $mysql =  apps(Connect::MYSQL,env('MYSQL_USER'),env('BASE'),env('MYSQL_PASSWORD'),'localhost','dump','imperium','.','views',[],['phinxlog'],[]);
} catch (Exception $e) {
    d($e->getMessage());
}

try {
    $pgsql = apps(Connect::POSTGRESQL, env('POSTGRESQ_USER'), env('BASE'), env('POSTGRESQL_PASSWORD'),'localhost' , 'dump', 'imperium','.','views',[],['phinxlog'],[]);
} catch (Exception $e) {
    d($e->getMessage());
}

try {
    $sqlite = apps(Connect::SQLITE, '', 'zen.sqlite3', "",'localhost', 'dump', 'imperium','.','views',[],['phinxlog','sqlite_sequence'],[]);
} catch (Exception $e) {
    d($e->getMessage());
}
