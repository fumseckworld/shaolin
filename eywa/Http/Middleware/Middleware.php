<?php

declare(strict_types=1);
namespace Eywa\Http\Middleware {

    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\ServerRequest;

    abstract class Middleware
    {
        /**
         *
         * Check the request
         *
         * @param ServerRequest $request
         *
         * @return void
         *
         * @throws Kedavra
         *
         */
        abstract public function check(ServerRequest $request): void;
    }
}