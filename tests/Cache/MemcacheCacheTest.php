<?php


namespace Testing\Cache;


use DI\DependencyException;
use DI\NotFoundException;
use Eywa\Cache\MemcacheCache;
use Eywa\Exception\Kedavra;
use PHPUnit\Framework\TestCase;

/**
 * Class RedisCacheTest
 * @package Testing\Cache
 */
class MemcacheCacheTest extends TestCase
{
    /**
     *
     */
    private MemcacheCache $cache;

    /**
     *
     */
    public function setUp(): void
    {
       $this->cache = new MemcacheCache();
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function test_empty()
    {
        $this->assertFalse($this->cache->get('a'));
        $this->assertFalse($this->cache->get('b'));
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function test_create()
    {
        $this->assertNotEmpty($this->cache->set('a','I am a view')->get('a'));
        $this->assertEquals('I am a view',$this->cache->get('a'));
    }


    /**
     * @throws Kedavra
     */
    public function test_remove()
    {
        $this->assertTrue($this->cache->has('a'));
        $this->assertTrue($this->cache->destroy('a'));
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function test_clear()
    {
        $this->assertNotEmpty($this->cache->set('a','I am a view')->get('a'));
        $this->assertEquals('I am a view',$this->cache->get('a'));
        $this->assertTrue($this->cache->clear());
        $this->assertFalse($this->cache->get('a'));
    }



}