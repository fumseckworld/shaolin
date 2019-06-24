<?php


namespace Testing;


use GuzzleHttp\Psr7\ServerRequest;
use Imperium\Exception\Kedavra;
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
     */
    public function test_token()
    {
        $first = $this->csrf->token();
        $second = $this->csrf->token();
        $this->assertFalse(different($first,$second));

        $this->expectException(Kedavra::class);
        app()->router(new ServerRequest(POST,'/commit'))->run();

        $this->assertTrue(different($first,$this->csrf->token()));
    }

    /**
     * @throws Kedavra
     */
    public function test_exception()
    {
        $this->expectException(Kedavra::class);
        app()->router((new ServerRequest(POST,'/commit')))->run();
    }
}