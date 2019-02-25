<?php

namespace Testing\router;

use Exception;
use GuzzleHttp\Psr7\ServerRequest;
use Imperium\Routing\Router;
use Testing\DatabaseTest;

class RouterTest extends DatabaseTest
{

    /**
     * @var ServerRequest
     */
    private $request;
    
    /**
     * @var Router
     */
    private $router;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        $this->request = new ServerRequest(GET,'/');
        $this->router = new Router($this->request);

    }


    /**
     * @throws Exception
     */
    public function test_exception()
    {
        $this->expectException(Exception::class);

        app()->router(new ServerRequest(POST,'/del'))->run();
    }

    public function test_success()
    {
        $this->assertNotEmpty(app()->router(new ServerRequest(GET,'/login'))->run());
    }
    public function test_callback()
    {
        $this->assertEquals('AdminController@login',Router::callback('login'));
    }
    public function test_admin()
    {
        $this->assertEquals('/',Router::admin('home'));
        $this->assertEquals('/query',Router::admin('query',POST));
    }

    public function test_name_not_found()
    {
        $this->expectException(Exception::class);
        name('alexandra');
        name('a',POST);
        name('b',GET);
    }
    public function test_name()
    {
        $this->assertEquals('/remove/:table/:id',name('remove'));
        $this->assertEquals('/',name('home'));
        $this->assertEquals('/login',name('login'));
        $this->assertEquals('/login',name('login',POST));

    }

}