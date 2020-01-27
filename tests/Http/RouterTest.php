<?php


namespace Testing\Http;


use DI\DependencyException;
use DI\NotFoundException;
use Eywa\Exception\Kedavra;
use Eywa\Http\Request\ServerRequest;
use Eywa\Http\Response\Response;
use Eywa\Http\Routing\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{

    public function setUp(): void
    {

    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function test_success()
    {
        $router = (new Router(new ServerRequest('/','GET')))->run();

        $this->assertNotEmpty($router->content());
        $this->assertTrue($router->success());



    }

    /**
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    public function test_post()
    {
        $router = (new Router(new ServerRequest('/send','POST')))->run();

        $this->assertNotEmpty($router->content());
        $this->assertTrue($router->success());


    }


    /**
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException*
     */
    public function test_404()
    {
        $router = (new Router(new ServerRequest('/sens','POST')))->run();
        $this->assertInstanceOf(Response::class,$router);
    }

    /**
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    public function test_param()
    {
        $router = (new Router(new ServerRequest('/hello/marc')))->run();
        $this->assertTrue($router->success());
        $this->assertStringContainsString('bonjour marc',$router->content());

    }
}