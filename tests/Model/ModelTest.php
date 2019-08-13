<?php

namespace Testing\Model;

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
	public function test_between()
	{
		$this->assertNotEmpty(Users::between(10,50)->display(1,15)->all());
		$this->assertNotEmpty(Users::between(10,50)->all());
		$this->assertNotEmpty(Users::between(10,50)->display(2,50)->get(0));
		$this->assertNotEmpty(Users::between(10,50)->display(2,50)->last());
		$this->assertNotEmpty(Users::between(10,50)->display(2,50)->first());
	}
	/**
	 * @throws Kedavra
	 */
    public function test_import()
    {
        $this->assertTrue((new Dump(true,[]))->dump());
        $this->assertTrue((new Import())->import());
    }
}