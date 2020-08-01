<?php

namespace Testing\Http;

use App\Controllers\WelcomeController;
use DI\DependencyException;
use DI\NotFoundException;
use Imperium\Exception\Kedavra;
use Imperium\Http\Request\Request;
use Imperium\Http\Routing\Route;
use Imperium\Testing\Unit;

class RouteTest extends Unit
{
    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    final public function testSuccess(): void
    {

        $this->success(
            $this->route(WelcomeController::class, 'run')->exec()->see('submit'),
            $this->route(WelcomeController::class, 'run')->exec()->ok()
        )->identical('run', $this->route(WelcomeController::class, 'run')->action())
            ->def($this->route(WelcomeController::class, 'run')->exec()->content())
            ->def($this->route(WelcomeController::class, 'run')->controller())
            ->is(Request::class, $this->route(WelcomeController::class, 'run')->args());
    }

    final public function testFail(): void
    {
        $this->throw(
            Kedavra::class,
            'The controller has not been found',
            function () {
                new Route('', 'run');
            }
        );
    }


    final public function testError(): void
    {
        $this->throw(
            Kedavra::class,
            'The a method has not been found in the App\Controllers\WelcomeController controller',
            function () {
                new Route(WelcomeController::class, 'a');
            }
        );
    }
}
