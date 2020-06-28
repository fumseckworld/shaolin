<?php

namespace Testing\Container;

use DI\Container;
use Imperium\Container\Ioc;
use Imperium\Exception\Kedavra;
use Imperium\Http\Request\Request;
use Imperium\Http\Response\Response;
use PHPUnit\Framework\TestCase;

class IocTest extends TestCase
{
    private Ioc $ioc;

    public function setUp(): void
    {
        $this->ioc = new Ioc();
    }

    public function testCall()
    {
        $this->assertEquals('hello', $this->ioc->call(function () {
            return 'hello';
        }));
    }

    public function testSet()
    {
        $this->assertInstanceOf(Ioc::class, $this->ioc->set('a', 'b')->set('b', 'a'));
    }

    public function testHas()
    {
        $this->assertTrue($this->ioc->has('a'));
        $this->assertTrue($this->ioc->set('a', 'b')->has('a'));
    }

    public function testGet()
    {
        $this->assertInstanceOf(Response::class, $this->ioc->get(Response::class));
    }

    public function testMake()
    {
        $this->assertInstanceOf(Response::class, $this->ioc->make(Response::class));
    }

    public function testIoc()
    {
        $this->assertInstanceOf(Container::class, $this->ioc->ioc());
    }
}
