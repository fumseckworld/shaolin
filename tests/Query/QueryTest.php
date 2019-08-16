<?php


namespace Testing\Query;


use App\Models\Users;
use DI\DependencyException;
use DI\NotFoundException;
use Imperium\Exception\Kedavra;
use Imperium\Model\Routes;
use Imperium\Query\Query;
use Imperium\Testing\Unit;
use PDO;

class QueryTest extends Unit
{
	/**
	 * @throws Kedavra
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
    public function test()
    {
        $this->assertNotEmpty(Query::from('users')->columns());
        $this->assertEquals(10,Query::from('users')->take(10)->sum());
        $this->assertEquals(10,Query::from('users')->different('id',10)->take(10,2)->sum());
        $this->assertEquals(10,Query::from('users')->select('id','lastname')->take(10,4)->sum());
        $this->assertEquals(10,Query::from('users')->select('id','lastname')->take(10,3)->by('lastname')->sum());
        $this->assertEquals(20,Query::from('users')->select('id','lastname')->between('id',1,50)->pdo(PDO::FETCH_ASSOC)->take(20,1)->by('id',ASC)->sum());
        $this->assertEquals('SELECT id, lastname FROM users     LIMIT 2,10 ',Query::from('users')->select('id','lastname')->take(10,2)->sql());
        $this->assertNotEmpty(Query::from('users')->like('a')->all());
    }
		
    public function test_query_primary_key()
	{
		$this->assertEquals('id',Query::from('users')->primary_key());
		$this->assertEquals('id',Routes::key());
		$this->assertEquals('id',Routes::primary());
	}
	public function test_query_not()
	{
		$this->assertNotEmpty(Query::from('users')->not('id',2,3,4,5,6,7)->all());
		$this->assertCount(5,Query::from('users')->not('id',2,3,4,5,6,7)->take(5)->all());
		$this->assertCount(5,Query::from('users')->where('id',DIFFERENT,5)->not('id',2,3,4,6,7,54)->take(5)->all());
		
	}
	public function test_query_or()
	{
		$this->assertNotEmpty(Query::from('users')->where('id','=',2)->or('id','=' ,4)->or('id','=',8)->all());
		$this->assertCount(3,Query::from('users')->where('id','=',2)->or('id','=' ,4)->or('id','=',8)->all());
		
	}
	public function test_query_and()
	{
		$x = Users::find(50);
		$this->assertNotEmpty(Query::from('users')->where('id','=',50)->and('firstname','=' ,$x->firstname)->all());
		$this->assertCount(1,Query::from('users')->where('id','=',50)->and('firstname','=' ,$x->firstname)->all());
	
	}
	public function test_query_only()
	{
		$x = Users::find(50);
		$this->assertNotEmpty(Query::from('users')->where('id',DIFFERENT,3)->only('firstname',$x->firstname)->all());
		$this->assertCount(1,Query::from('users')->only('firstname',$x->firstname)->all());
	
	}
	public function test_query_like()
	{
		$this->assertNotEmpty(Query::from('users')->like('a')->all());
		$this->assertNotEmpty(Routes::search('a'));
		$this->assertCount(5,Query::from('users')->not('id',2,3,4,5,6,7)->take(5)->all());
		$this->assertCount(5,Query::from('users')->where('id',DIFFERENT,5)->not('id',2,3,4,6,7,54)->take(5)->all());
		
	}
	/**
	 * @throws DependencyException
	 * @throws Kedavra
	 * @throws NotFoundException
	 */
    public function test_exec()
    {
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('The current mode is not valid');
        Query::from('users')->mode(1)->all();
    }
}