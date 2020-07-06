<?php

namespace Testing\Request;

use Imperium\Exception\Kedavra;
use Imperium\Http\Parameters\Bag;
use Imperium\Http\Parameters\UploadedFile;
use Imperium\Http\Request\ServerRequest;
use Imperium\Testing\Unit;

class ServerRequestTest extends Unit
{
    public function testMatch()
    {
        $this->success((new ServerRequest('/'))->match('/'))
            ->failure((new ServerRequest('/news'))->match('/'));
    }

    public function testException()
    {
        $this->throw(Kedavra::class, 'The method used is not supported');
        new ServerRequest('/', 'put');
        new ServerRequest('/', 'delete');
        new ServerRequest('/', '');
    }

    public function testMethod()
    {
        $this->identical('GET', (new ServerRequest('/', 'get'))->method())
            ->identical('POST', (new ServerRequest('/', 'post'))->method());
    }

    public function testUrl()
    {
        $this->identical('/', (new ServerRequest('/'))->url());
    }

    public function testSubmitted()
    {
        $this->failure((new ServerRequest('/'))->submitted())
            ->success((new ServerRequest('/', 'post'))->submitted());
    }

    public function testLocal()
    {
        $this->success((new ServerRequest('/'))->local());
    }

    public function testBag()
    {
        $server = new ServerRequest('/');
        $this->is(
            Bag::class,
            $server->query(),
            $server->cookie(),
            $server->request(),
            $server->server()
        );
        $this->is(UploadedFile::class, $server->files());
    }
    public function testHasToken()
    {
        $this->failure((new ServerRequest('/'))->hasToken());
    }

    public function testGenerate()
    {
        $this->is(ServerRequest::class, ServerRequest::generate());
    }
}
