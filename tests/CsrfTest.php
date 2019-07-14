<?php


namespace Testing;


use GuzzleHttp\Psr7\ServerRequest;
use Imperium\Exception\Kedavra;
use Imperium\Routing\Router;
use Imperium\Security\Csrf\Csrf;
use Imperium\Session\ArraySession;
use PHPUnit\Framework\TestCase;

/**
 * Class CsrfTest
 * @package Testing
 */
class CsrfTest extends TestCase
{
    /**
     * @var Csrf
     */
    private $csrf;

    /**
     *
     */
    public function setUp(): void
    {
        $this->csrf = new Csrf(new ArraySession());
    }

    /**
     * @throws Kedavra
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function test_token()
    {
        $first = $this->csrf->token();
        $second = $this->csrf->token();
        $this->assertEquals($first,$second);

        $this->expectException(Kedavra::class);
        $router = new Router(new ServerRequest(POST,'/commit'));
        $router->search()->send();
    }

    /**
     * @throws Kedavra
     */
    public function test_exception()
    {
        $this->expectException(Kedavra::class);
        (new Router(new ServerRequest(POST,'/commit')))->search()->call()->send();
    }
}