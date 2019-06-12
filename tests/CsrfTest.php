<?php


namespace Testing;


use Imperium\Security\Csrf\Csrf;
use Imperium\Session\ArraySession;
use PHPUnit\Framework\TestCase;

class CsrfTest extends TestCase
{
    /**
     * @var Csrf
     */
    private $csrf;

    public function setUp(): void
    {
        $this->csrf = new Csrf(new ArraySession());
    }

    public function test_token()
    {
        $first = $this->csrf->token();
        $second = $this->csrf->token();
        $this->assertEquals($first,$second);
    }
}