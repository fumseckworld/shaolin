<?php

namespace Testing\router;

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

    public function setUp(): void
    {
        $this->request = new ServerRequest(GET,'/');
        $this->router = new Router($this->request,'Shaolin\\Controllers','core');
      
    }



    public function test_url()
    {
        $this->assertEquals('/remove/:table/:id',name('remove'));
        $this->assertEquals('/',name('home'));
    }

}