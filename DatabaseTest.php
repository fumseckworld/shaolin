<?php


namespace Testing;


use Imperium\Connexion\Connect;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{

    const MYSQL_USER = 'root';
    const MYSQL_PASS = '';

    const POSTGRESQL_USER = 'postgres';
    const POSTGRESQL_PASS = '';

    const BASE  = 'zen';
    const MODE = PDO::FETCH_OBJ;

    protected $class = 'btn btn-outline-primary';
    /**
     * @var \Imperium\Imperium
     */
    protected $mysql;

    /**
     * @var \Imperium\Imperium
     */
    protected $pgsql;

    /**
     * @var \Imperium\Imperium
     */
    protected $sqlite;

    protected $base = 'zen';

    /**
     * @var string
     */
    protected $table = 'patients';

    /**
     * @var \Imperium\Databases\Eloquent\Query\Query
     */
    protected $sql;
    /**
     * @var null|\PDO
     */
    protected $mysqlPdo;

    /**
     * @var null|\PDO
     */
    protected $sqlitePdo;

    /**
     * @var null|\PDO
     */
    protected $pgsqlPdo;



    /**
     * @throws \Exception
     */
    public function setup()
    {

        $this->mysqlPdo = connect(Connect::MYSQL,self::BASE,self::MYSQL_USER,self::MYSQL_PASS,self::MODE,'dump')->instance();
        $this->pgsqlPdo = connect(Connect::POSTGRESQL,self::BASE,self::POSTGRESQL_USER,self::POSTGRESQL_PASS,self::MODE,'dump')->instance();
        $this->sqlitePdo = connect(Connect::SQLITE,self::BASE,'','',self::MODE,'dump')->instance();

        $this->mysql = instance(Connect::MYSQL,self::MYSQL_USER,self::BASE,self::MYSQL_USER,self::MYSQL_PASS,$this->table);
        $this->pgsql = instance(Connect::POSTGRESQL,self::POSTGRESQL_USER,self::BASE,self::POSTGRESQL_USER,self::POSTGRESQL_PASS,$this->table);
        $this->sqlite = instance(Connect::SQLITE,'',self::BASE,'','',$this->table,self::MODE);
    }
}