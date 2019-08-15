<?php


namespace Testing;


use DI\DependencyException;
use DI\NotFoundException;
use GuzzleHttp\Psr7\ServerRequest;
use Imperium\Exception\Kedavra;
use Imperium\Routing\Router;
use Imperium\Security\Csrf\Csrf;
use Imperium\Security\Csrf\CsrfMiddleware;
use Imperium\Session\ArraySession;
use PHPUnit\Framework\TestCase;
use Imperium\Testing\Unit;

/**
 * Class CsrfTest
 * @package Testing
 */
class CsrfTest extends Unit
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
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
    public function test_exception()
    {
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('We have not found the csrf token');
        $this->visit('/add', POST)->call()->send();
    }
}
  