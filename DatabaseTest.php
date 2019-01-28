<?php



namespace Testing;

require_once 'vendor/autoload.php';
require_once 'config.php';


use Imperium\App;

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{


    const MYSQL_USER = 'root';
    const MYSQL_PASS = 'root';
    const POSTGRESQL_USER = 'postgres';
    const POSTGRESQL_PASS = 'postgres';

    protected $base = 'zen';
    protected $class = 'btn btn-primary';


    public function mysql(): App
    {
        global $mysql;
        return $mysql;

    }
    public function postgresql(): App
    {
        global $pgsql;
        return $pgsql;
    }

    public function sqlite(): App
    {
        global $sqlite;
        return $sqlite;
    }

}
