<?php

namespace Testing\Model;



use Imperium\Testing\Unit;
use Shaolin\Models\Users;

class ModelTest extends Unit
{

    public function test_all()
    {
        $this->assertNotEmpty(Users::all());
        $this->assertEquals(100,Users::count());
        $this->assertTrue(Users::destroy(1));
        $this->assertEquals(99,Users::count());
        $this->assertEquals(10,Users::different('id',5)->take(10)->offset(2)->sum());
        $this->assertEquals(49,Users::where('id',INFERIOR_OR_EQUAL,50)->sum());

    }
}