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

    protected $table = 'doctors';
    protected $base = 'zen';
    protected $class = 'btn btn-outline-primary';
    protected $second_table = 'patients';

    use \config;

}