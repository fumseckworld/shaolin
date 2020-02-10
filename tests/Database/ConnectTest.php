<?php


namespace Testing\Database {

    use Eywa\Application\App;
    use Eywa\Database\Connexion\Connect;
    use PDO;
    use PDOException;
    use PHPUnit\Framework\TestCase;

    class ConnectTest extends TestCase
    {

        private Connect $connect;

        private App $app;

        public function setUp(): void
        {
            $this->connect = ioc(Connect::class);
            $this->app = app();
        }


        public function test_env()
        {
            $this->assertEquals($this->app->env('DB_DRIVER'),$this->connect->driver());
            $this->assertEquals($this->app->env('DB_HOST'),$this->connect->hostname());
            $this->assertEquals($this->app->env('DB_NAME'),$this->connect->base());
            $this->assertEquals($this->app->env('DB_PORT'),$this->connect->port());
            $this->assertEquals($this->app->env('DB_USERNAME'),$this->connect->username());
            $this->assertEquals($this->app->env('DB_PASSWORD'),$this->connect->password());
        }

        public function test_queries()
        {
            $this->assertTrue($this->connect->set('SHOW TABLES')->execute());
            $this->assertNotEmpty($this->connect->set('SHOW TABLES')->get(PDO::FETCH_OBJ));
            $this->assertNotEmpty($this->connect->set('SHOW TABLES')->fetch());
        }

        public function test_is()
        {
            $this->assertTrue($this->connect->mysql());
            $this->assertFalse($this->connect->sqlite());
            $this->assertFalse($this->connect->postgresql());
        }

        public function test_not()
        {
            $this->assertFalse($this->connect->not(MYSQL));
            $this->assertTrue($this->connect->not(POSTGRESQL));
            $this->assertTrue($this->connect->not(SQLITE));
            $this->assertTrue($this->connect->not(SQL_SERVER));
        }


        public function test_exception()
        {
            $this->expectException(PDOException::class);
            connect(POSTGRESQL,'zen');
        }
        public function test_error()
        {
            $this->expectException(PDOException::class);
            $this->connect->set('SHOW')->get(PDO::FETCH_OBJ);
        }


        public function test_postgresql_exception()
        {
            $this->expectException(PDOException::class);
            connect(POSTGRESQL,'');
        }
    }
}