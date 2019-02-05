<?php

namespace Testing\router;

use GuzzleHttp\Psr7\ServerRequest;
use Imperium\Router\Router;
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

    public function setUp()
    {
        $this->request = new ServerRequest(GET,'/');
        $this->router = new Router($this->request);
      
    }

    /**
     * @throws \Exception
     */
    public function test_run()
    {
        $this->assertNotEmpty($this->router->run());

    }

    public function test_url()
    {
        $this->assertEquals('/remove/:table/:id',url('remove'));
        $this->assertEquals('/',url('home'));
    }

}