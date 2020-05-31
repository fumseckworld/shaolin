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
        $this->assertTrue(DB::prod()->mysql());
        $this->assertFalse(DB::prod()->postgresql());
        $this->assertFalse(DB::prod()->sqlite());

        $this->assertTrue(DB::dev()->postgresql());
        $this->assertFalse(DB::dev()->mysql());
        $this->assertFalse(DB::dev()->sqlite());

        $this->assertTrue(DB::test()->sqlite());
        $this->assertFalse(DB::test()->mysql());
        $this->assertFalse(DB::test()->postgresql());
    }

    public function testExecAndGet()
    {
        $this->assertTrue(DB::prod()->exec('SHOW TABLES'));
        $this->assertTrue(DB::dev()->exec('SELECT * FROM pg_database'));
        $this->assertTrue(DB::test()->exec("SELECT name FROM sqlite_master WHERE type='table';"));
        $this->assertEmpty(DB::prod()->get('SHOW TABLES'));
        $this->assertEmpty(DB::dev()->get('SELECT * FOR'));
        $this->assertNotEmpty(DB::dev()->get('SELECT * FROM pg_database'));
        $this->assertEmpty(DB::test()->get("SELECT name FROM sqlite_master WHERE type='table';"));
    }
}
