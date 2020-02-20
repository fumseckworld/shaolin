<?php

declare(strict_types=1);
namespace Eywa\Http\Middleware {


    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\ServerRequest;
    use Eywa\Http\Response\Response;

    abstract class Middleware
    {
        /**
         *
         * Check the request
         *
         * @param ServerRequest $request
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        abstract public function check(ServerRequest $request): Response;

        /**
         *
         * Execute the next middleware
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function next(): Response
        {
            return (new Response(''))->send();
        }

    }
}