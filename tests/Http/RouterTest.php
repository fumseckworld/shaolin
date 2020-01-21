<?php


namespace Testing\Http;


use DI\DependencyException;
use DI\NotFoundException;
use Eywa\Exception\Kedavra;
use Eywa\Http\Request\Server;
use Eywa\Http\Response\RedirectResponse;
use Eywa\Http\Response\Response;
use Eywa\Http\Routing\Router;
use Eywa\Http\Routing\RouteResult;
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
        $router = (new Router(new Server('/','GET')))->run();

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
        $router = (new Router(new Server('/send','POST')))->run();

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
        $router = (new Router(new Server('/sens','POST')))->run();


        $this->assertInstanceOf(Response::class,$router);


    }
}