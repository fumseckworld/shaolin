<?php

namespace Testing\Request;

use Imperium\Exception\Kedavra;
use Imperium\Http\Parameters\Bag;
use Imperium\Http\Parameters\UploadedFile;
use Imperium\Http\Request\ServerRequest;
use PHPUnit\Framework\TestCase;

class ServerRequestTest extends TestCase
{
    public function testMatch()
    {
        $this->assertTrue((new ServerRequest('/'))->match('/'));
        $this->assertFalse((new ServerRequest('/news'))->match('/'));
    }

    public function testException()
    {
        $this->expectException(Kedavra::class);
        new ServerRequest('/', 'put');
        new ServerRequest('/', 'delete');
        new ServerRequest('/', '');
    }

    public function testMethod()
    {
        $this->assertEquals('GET', (new ServerRequest('/', 'get'))->method());
        $this->assertEquals('POST', (new ServerRequest('/', 'post'))->method());
    }

    public function testUrl()
    {
        $this->assertEquals('/', (new ServerRequest('/'))->url());
    }

    public function testSubmitted()
    {
        $this->assertFalse((new ServerRequest('/'))->submitted());
        $this->assertTrue((new ServerRequest('/', 'post'))->submitted());
    }

    public function testLocal()
    {
        $this->assertTrue((new ServerRequest('/'))->local());
    }

    public function testBag()
    {
        $server = new ServerRequest('/');
        $this->assertInstanceOf(Bag::class, $server->query());
        $this->assertInstanceOf(Bag::class, $server->cookie());
        $this->assertInstanceOf(Bag::class, $server->request());
        $this->assertInstanceOf(Bag::class, $server->server());
        $this->assertInstanceOf(UploadedFile::class, $server->files());
    }
    public function testHasToken()
    {
        $this->assertFalse((new ServerRequest('/'))->hasToken());
    }

    public function testGenerate()
    {
        $this->assertInstanceOf(ServerRequest::class, ServerRequest::generate());
    }
}
