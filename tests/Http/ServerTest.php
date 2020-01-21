<?php


namespace Testing\Http;


use Eywa\Exception\Kedavra;
use Eywa\Http\Request\Request;
use Eywa\Http\Request\Server;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{

    /**
     * @var Server
     */
    private Server $request;

    /**
     * @throws Kedavra
     */
    public function setup(): void
    {
        $this->request = Server::generate();
    }

    public function test()
    {
        $this->assertEquals(GET,$this->request->method());
        $this->assertEquals('/',$this->request->url());
        $this->assertInstanceOf(Request::class,$this->request->request());
    }
}