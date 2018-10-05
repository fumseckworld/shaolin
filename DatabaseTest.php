<?php



namespace Testing;

include_once 'config.php';

use Imperium\Imperium;
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

    public function mysql(): Imperium
    {
        return instance_mysql();
    }

    public function postgresql(): Imperium
    {
        return instance_pgsql();
    }

    public function sqlite(): Imperium
    {
        return instance_sqlite();
    }

}