<?php


namespace Testing\Query;


use Imperium\Exception\Kedavra;
use Imperium\Query\Query;
use Imperium\Testing\Unit;
use PDO;

class QueryTest extends Unit
{

    public function test()
    {
        $this->assertNotEmpty(Query::from('users')->columns());
        $this->assertEquals(10,Query::from('users')->take(10)->offset(0)->sum());
        $this->assertEquals(10,Query::from('users')->different('id',10)->take(10)->offset(2)->sum());
        $this->assertEquals(10,Query::from('users')->select('id','lastname')->take(10)->offset(2)->sum());
        $this->assertEquals(10,Query::from('users')->select('id','lastname')->take(10)->offset(2)->by('lastname')->sum());
        $this->assertEquals(20,Query::from('users')->select('id','lastname')->between('id',1,50)->pdo(PDO::FETCH_ASSOC)->take(20)->offset(1)->by('id',ASC)->sum());
        $this->assertEquals('SELECT id, lastname FROM users     LIMIT 10 OFFSET 2',Query::from('users')->select('id','lastname')->take(10)->offset(2)->sql());
        $this->assertNotEmpty(Query::from('users')->like('a')->all());
    }

    public function test_exec()
    {
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('The current mode is not valid');
        Query::from('users')->mode(1)->all();
    }
}