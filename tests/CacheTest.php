<?php


namespace Testing;


use Imperium\Testing\Unit;

class CacheTest extends Unit
{

    public function test_has()
    {
        $key = 'alexandra';
        $this->assertTrue($this->cache()->clear());
        $this->assertFalse($this->cache()->has($key));
        $this->assertTrue($this->cache()->not($key));
        $this->assertTrue($this->cache()->set($key, $key));
        $this->assertNotEmpty($this->cache()->infos()->all());
        $this->assertFalse($this->cache()->def($key,$key));
        $this->assertEquals($key,$this->cache()->get($key));
        $this->assertTrue($this->cache()->remove($key));
        $this->assertTrue($this->cache()->def($key,$key));
        $this->assertTrue($this->cache()->remove($key));

    }
}