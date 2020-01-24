<?php


namespace Testing\Database;


use App\Models\User;
use DI\DependencyException;
use DI\NotFoundException;
use Eywa\Database\Seed\Seed;
use Eywa\Database\Table\Table;
use Eywa\Exception\Kedavra;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class SeedTest extends TestCase
{

    public function tearDown(): void
    {
        shell_exec('mysql -ueywa -peywa eywa < sql\mysql\users.sql');
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function test()
    {
        $x =   Seed::from('users')->each(function (Generator $generator,Table $table,Seed $seed)
        {
            foreach ($table->columns() as $column)
            {
                switch ($column)
                {
                    case 'created_at':
                        $seed->set($column,now()->toDateTimeString());
                    break;
                    case different($column,$table->primary()):
                        $seed->set($column,$generator->word());
                    break;
                    default:
                        $seed->set($column,'NULL');
                    break;

                }
            }
        });

        $this->assertEquals(100,$x->limit());
        $this->assertTrue($x->seed());
        $this->assertEquals(200,User::sum());

    }
}