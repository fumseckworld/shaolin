<?php



namespace Testing;

require_once 'config.php';

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{


    const MYSQL_USER = 'root';
    const MYSQL_PASS = 'root';
    const POSTGRESQL_USER = 'postgres';
    const POSTGRESQL_PASS = 'postgres';

    protected $base = 'zen';
    protected $table;
    protected $class = 'btn btn-primary';

    public function mysql()
    {
        global $mysql;
        return $mysql;

    }
    public function postgresql()
    {
        global $pgsql;
        return $pgsql;
    }

    public function sqlite()
    {
        global $sqlite;
        return $sqlite;
    }

}