<?php

use Imperium\Connexion\Connect;

require_once  'vendor/autoload.php';

$base = 'zen';
$mode = PDO::FETCH_OBJ;
$table  = 'doctors';



$mysql  = instance(Connect::MYSQL,'root',$base,'root',$mode,'dump',$table);
$pgsql  = instance(Connect::POSTGRESQL,'postgres',$base,'postgres',$mode,'dump',$table);
$sqlite = instance(Connect::SQLITE,  '','zen.sqlite3','',$mode,'dump',$table);


function instance_mysql():  \Imperium\Imperium
{
     global $mysql;
    
     return $mysql;
}


function instance_pgsql():  \Imperium\Imperium
{
     global $pgsql;

     return $pgsql;
}

function instance_sqlite():  \Imperium\Imperium
{
     global $sqlite;

     return $sqlite;
}

