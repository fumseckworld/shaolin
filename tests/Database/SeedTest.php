<?php


namespace Testing\Database;



use App\Models\User;
use Base\Seeds\UserSeeder;
use DI\DependencyException;
use DI\NotFoundException;
use Eywa\Exception\Kedavra;
use PHPUnit\Framework\TestCase;

class SeedTest extends TestCase
{

    public function tearDown(): void
    {
        $file = base('sql','mysql','users.sql');
        shell_exec("mysql -ueywa -peywa eywa < $file");

        $file = base('sql','pgsql','users.sql');
        shell_exec("psql -U eywa eywa < $file");
    }

    /**
     * @throws Kedavra
     */
    public function test_mysql()
    {

        $this->assertTrue($this->driver(MYSQL));
        $sum = User::sum();
        $this->assertTrue(call_user_func_array([UserSeeder::class,'seed'],[]));
        $this->assertNotEquals($sum,connect(env('DEVELOP_DB_DRIVER'),env('DEVELOP_DB_NAME'),env('DEVELOP_DB_USERNAME'),env('DEVELOP_DB_PASSWORD'))->query('SELECT COUNT("id") from users'));

    }

    /**
     * @throws Kedavra
     */
    public function test_pgsql()
    {
        $this->assertTrue($this->driver(POSTGRESQL));
        $sum = User::sum();
        $this->assertTrue(call_user_func([UserSeeder::class,'seed']));
        $this->assertNotEquals(  $sum,connect(env('DEVELOP_DB_DRIVER'),env('DEVELOP_DB_NAME'),env('DEVELOP_DB_USERNAME'),env('DEVELOP_DB_PASSWORD'))->query('SELECT COUNT("id") from users'));

    }
    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function test_sqlite()
    {
        $this->assertTrue($this->driver(SQLITE));
        $sum = User::sum();
        $this->assertTrue(call_user_func([UserSeeder::class, 'seed']));

        $this->assertNotEquals(  $sum , connect(env('DEVELOP_DB_DRIVER'), env('DEVELOP_DB_NAME'), env('DEVELOP_DB_USERNAME'), env('DEVELOP_DB_PASSWORD'))->query('SELECT COUNT("id") from users'));
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function test()
    {
        $sum = User::sum();
        $this->assertTrue($this->driver(MYSQL));
        $this->assertTrue(call_user_func([UserSeeder::class,'seed']));
        $this->assertNotEquals(  $sum,connect(env('DEVELOP_DB_DRIVER'),env('DEVELOP_DB_NAME'),env('DEVELOP_DB_USERNAME'),env('DEVELOP_DB_PASSWORD'))->query('SELECT COUNT("id") from users'));
    }

    private function driver(string $driver)
    {
        $f = fopen('.env','r');
        $lines = collect();
        if ($f)
        {
            while (!feof($f))
            {
                $lines->push(fgets($f));
            }
            fclose($f);
        }

        $f = fopen('.env','w+');
        if ($f)
        {
            foreach ($lines->all() as $line)
            {
                $x = collect(explode('=',$line))->first();
                if (equal($x,'DB_DRIVER'))
                {
                    fputs($f,"$x=$driver\n");

                }else{

                    if(equal($driver,POSTGRESQL) && equal($x,'DB_PORT'))
                    {
                        fputs($f,"DB_PORT=5432\n");
                    }else{

                        if (equal($driver,MYSQL) && equal($x,'DB_PORT'))
                        {
                            fputs($f,"DB_PORT=3306\n");
                        }else{
                            fputs($f,$line);
                        }
                    }
                }
            }

            return fclose($f);
        }
        throw new Kedavra('fail to change .env values');
    }
}