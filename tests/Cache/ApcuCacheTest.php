<?php


namespace Testing\Cache;


use Eywa\Cache\ApcuCache;
use Eywa\Testing\Unit;

class ApcuCacheTest extends Unit
{
    /**
     *
     */
    private ApcuCache $cache;

    public function setUp(): void
    {
       $this->cache = new ApcuCache();
    }

    public function test_empty()
    {
        $this->assertFalse($this->cache->get('a'));
        $this->assertFalse($this->cache->get('b'));
    }

    public function test_create()
    {
        $this->assertNotEmpty($this->cache->set('a','I am a view')->get('a'));
        $this->assertEquals('I am a view',$this->cache->get('a'));
    }


    public function test_remove()
    {
        $this->assertTrue($this->cache->has('a'));
        $this->assertTrue($this->cache->destroy('a'));
    }
    public function test_clear()
    {
        $this->assertNotEmpty($this->cache->set('a','I am a view')->get('a'));
        $this->assertEquals('I am a view',$this->cache->get('a'));
        $this->assertTrue($this->cache->clear());
        $this->assertFalse($this->cache->get('a'));
    }



}