<?php

namespace Testing\Model;

use DI\DependencyException;
use DI\NotFoundException;
use Imperium\Dump\Dump;
use Imperium\Exception\Kedavra;
use Imperium\Import\Import;
use Imperium\Testing\Unit;
use App\Models\Users;
use stdClass;

class ModelTest extends Unit
{
	/**
	 * @throws Kedavra
	 */
    public function test_all()
    {
        $this->assertNotEmpty(Users::all());
        $this->assertNotEmpty(Users::paginate([$this,'records'],1,10));
        $this->assertEquals(99,Users::count());
        $this->assertNotEmpty(Users::only('id'));
        $this->assertInstanceOf(stdClass::class,Users::get(1));
        $this->assertNotEmpty(Users::search('a'));
        $columns = Users::columns();
        $this->assertNotEmpty($columns);
        $this->assertContains('id',$columns);
        $this->assertContains('firstname',$columns);
        $this->assertContains('lastname',$columns);
        $this->assertContains('password',$columns);
        
        $this->assertTrue(Users::destroy(1));
        $this->assertEquals(98,Users::count());
        $this->assertEquals(10,Users::different(5)->take(10)->offset(2)->sum());
        $this->assertEquals(48,Users::where('id',INFERIOR_OR_EQUAL,50)->sum());

    }
	
	/**
	 * @throws Kedavra
	 */
	public function test_between()
	{
		$this->assertNotEmpty(Users::between(10,50)->paginate([$this,'records'],1,10));
		
	}
	
	/**
	 * @throws Kedavra
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
    public function test_import()
    {
        $this->assertTrue((new Dump(true,[]))->dump());
        $this->assertTrue((new Import())->import());
    }
    
    public function records($key,$item)
	{
	
		return '<header><h2>'.$item->firstname.' '. $item->lastname.'</h2></header><div class="text-center"><a href="mailto:'.$item->email.'"  class="btn-hollow"> contact</a></div>';
		
	}
}