<?php


namespace Testing\Database;


use App\Seeders\UserSeeder;
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
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function test()
    {
        $this->assertTrue(call_user_func([UserSeeder::class,'seed']));
        $this->assertEquals(200,connect(env('DB_DRIVER'),env('DB_NAME'),env('DB_USERNAME'),env('DB_PASSWORD'))->query('SELECT COUNT("id") from users'));

    }
}