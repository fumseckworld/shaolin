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
        $this->assertInstanceOf(stdClass::class,Users::by(1));
        $this->assertNotEmpty(Users::search('a'));
        
        $this->assertTrue(Users::destroy(1));
        $this->assertEquals(98,Users::count());
        $this->assertEquals(10,Users::different(5)->take(10)->offset(2)->sum());
        $this->assertEquals(48,Users::where('id',INFERIOR_OR_EQUAL,50)->sum());

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