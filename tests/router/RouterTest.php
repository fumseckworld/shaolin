<?php

namespace Testing\router;

use Imperium\Router\Router;
use Testing\DatabaseTest;

class RouterTest extends DatabaseTest
{


    /**
     * @throws \Exception
     */
    public function test_run()
    {

        $router = new Router('/','Testing','GET');

        $router->add('/',function (){return "salut";},"home",Router::METHOD_GET);

        $this->assertCount(1,$router->routes(Router::METHOD_GET));

        $router->add('lorem',function (){},'lorem',Router::METHOD_GET);

        $this->assertCount(2,$router->routes(Router::METHOD_GET));

        $router->add('linkin',function (){},'linkin',Router::METHOD_POST);

        $this->assertCount(1,$router->routes(Router::METHOD_POST));

        $router->add('linkin-park',function (){},'linkina',Router::METHOD_POST);

        $this->assertCount(2,$router->routes(Router::METHOD_POST));

        $this->assertEquals('salut',$this->mysql()->run($router));

        $router = new Router('show/50-mon-article','Testing','GET');

        $router->add('show/:id-:slug','Controller@show','show','GET',true,['id','slug'],Router::NUMERIC,Router::SLUG);

        $this->assertEquals('show 50',$router->run());


    }

    public function test_exption()
    {
        $router = new Router('shows/50','Testing','GET');

        $router->add('a',function (){},'a','GET');


        $this->expectException(\Exception::class);
        $router->run();
        $router->add('aes',function (){},'a','GET');


    }
}