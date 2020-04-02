<?php

namespace Eywa\Security\Middleware {


    use Closure;
    use Eywa\Http\Middleware\Middleware;
    use Eywa\Http\Request\ServerRequest;
    use Eywa\Http\Response\Response;

    class CsrfMiddleware extends Middleware
    {

        /**
         * @inheritDoc
         */
        public function handle(ServerRequest $request, Closure $next): Response
        {
            return $next($request);
        }
    }
}
