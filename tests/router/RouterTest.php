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
        $this->router = new Router($this->request,'Testing');
      
    }

    /**
     * @throws \Exception
     */
    public function test_run()
    {

        $this->router->add('/',function (){return "salut";},"home",GET);

        $this->assertCount(1,$this->router->routes(GET));

        $this->router->add('lorem',function (){},'lorem',GET);

        $this->assertCount(2,$this->router->routes(GET));

        $this->router->add('linkin',function (){},'linkin',POST);

        $this->assertCount(1,$this->router->routes(POST));

        $this->router->add('linkin-park',function (){},'linkina',POST);

        $this->assertCount(2,$this->router->routes(POST));

        $this->assertEquals('salut',$this->router->run());

        $request = new ServerRequest(GET,'show/50-mon-article');
        $this->router = new Router($request,'Testing');

        $this->router->add('show/:id-:slug','Controller@display','show','GET',true,['id','slug'],NUMERIC,Router::SLUG);

        $this->assertEquals('50 mon-article',$this->router->run());


    }

    public function tests_method_not_exist()
    {
        $this->expectException(\Exception::class);

        $this->router->add('/','a@a','a',GET);
        $this->router->run();
    }
    
    public function test_url()
    {
        $this->router->add('eva',function (){},'eva',GET);
        $this->assertEquals('eva',url('eva'));
    }
    
    public function test_exception_two_name()
    {

        $this->expectException(\Exception::class);

        $this->router->add('a',function (){},'a','GET');
        $this->router->add('a',function (){},'a','GET');

        $this->router->run();


    }
}