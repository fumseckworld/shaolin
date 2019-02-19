<?php



namespace Testing;

require_once 'vendor/autoload.php';

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


    /**
     * @return App
     * @throws \Exception
     */
    public function mysql(): App
    {
       return app();
    }

    /**
     * @return App
     * @throws \Exception
     */
    public function postgresql(): App
    {
        return  app();
    }

    /**
     * @return App
     * @throws \Exception
     */
    public function sqlite(): App
    {
        return app();
    }

}
