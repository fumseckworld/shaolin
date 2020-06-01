<?php

namespace Testing\Container;

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

    public function testException()
    {
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('The mode is invalid please use the correct mode');
        $this->ioc->add(8, 'a', function () {
        });
        $this->ioc->add(81, 'a', function () {
        });
    }
    public function testMakeNotFound()
    {
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('Class not found');
        $this->ioc->add(MAKE, 'A', function () {
        });
    }

    public function testInitNotFound()
    {
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('Class not found');
        $this->ioc->add(INIT, 'A', function () {
        });
    }

    public function testEmpty()
    {
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('Parameters type, key, callback cannot be not define');
        $this->ioc->add(0, '', function () {
        });
    }

    public function testSymbolClass()
    {
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('This mode should no take the namespace but a symbol');
        $this->ioc->add(SYMBOL, Request::class, function () {
        });
    }

    public function testValid()
    {
        $this->assertInstanceOf(
            Ioc::class,
            $this->ioc->add(MAKE, Response::class, function () {
            })->add(INIT, Request::class, function () {
            })->add(SYMBOL, 'router', function () {
            })
        );
    }
}
