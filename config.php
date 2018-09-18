<?php

use Imperium\Connexion\Connect;

require_once  'vendor/autoload.php';

$base = 'zen';
$mode = PDO::FETCH_OBJ;
$table  = 'doctors';



$mysql  = instance(Connect::MYSQL,'root',$base,'',$mode,'dump',$table);
$pgsql  = instance(Connect::POSTGRESQL,'postgres',$base,'',$mode,'dump',$table);
$sqlite = instance(Connect::SQLITE,  '',$base,'',$mode,'dump',$table);


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

