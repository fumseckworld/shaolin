<?php

namespace Testing\Connect;

use Imperium\Database\DB;
use PHPUnit\Framework\TestCase;

class ConnectTest extends TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(DB::class, DB::dev());
        $this->assertInstanceOf(DB::class, DB::prod());
        $this->assertInstanceOf(DB::class, DB::test());
    }

    public function testDriver()
    {
        $this->assertFalse(DB::prod()->mysql());
        $this->assertFalse(DB::prod()->postgresql());
        $this->assertFalse(DB::prod()->sqlite());
    }

    public function testExecAndGet()
    {
        $this->assertTrue(DB::prod()->exec('SHOW TABLES'));
        $this->assertEmpty(DB::prod()->get('SHOW TABLES'));
    }
}
