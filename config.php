<?php

use Imperium\Connexion\Connect;

require_once  'vendor/autoload.php';

$base = 'zen';
$mode = PDO::FETCH_OBJ;
$table  = 'doctors';



$mysql  = instance(Connect::MYSQL,'root',$base,'root','',$table);
$pgsql  = instance(Connect::POSTGRESQL,'postgres',$base,'postgres','',$table);
$sqlite = instance(Connect::SQLITE,  '',$base,'','',$table);


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

