<?php

namespace App\Middleware\Security {

    use Closure;
    use Eywa\Http\Middleware\Middleware;
    use Eywa\Http\Request\ServerRequest;
    use Eywa\Http\Response\Response;

    class Csrf extends Middleware
    {
        /**
         * @inheritDoc
         */
        public function handle(ServerRequest $request, Closure $next): Response
        {
            is_true($request->submited() && $request->missingToken(), true, 'The csrf token has not been found');
            return $next($request);
        }
    }
}
