<?php

namespace Testing\Container;

use DI\Container;
use Imperium\Container\Ioc;
use Imperium\Exception\Kedavra;
use Imperium\Http\Request\Request;
use Imperium\Http\Response\Response;
use Imperium\Testing\Unit;

class IocTest extends Unit
{
    private Ioc $ioc;

    public function setUp(): void
    {
        $this->ioc = new Ioc();
    }

    public function testCall()
    {
        $this->identic('hello', $this->ioc->call(function () {
            return 'hello';
        }));
    }

    public function testSet()
    {
        $this->is(Ioc::class, $this->ioc->set('a', 'b')->set('b', 'a'));
    }

    public function testHas()
    {
        $this->success($this->ioc->has('a'))->success($this->ioc->set('a', 'b')->has('a'));
    }

    public function testGet()
    {
        $this->is(Response::class, $this->ioc->get(Response::class));
    }

    public function testMake()
    {
        $this->is(Response::class, $this->ioc->make(Response::class));
    }

    public function testIoc()
    {
        $this->is(Container::class, $this->ioc->ioc());
    }
}
