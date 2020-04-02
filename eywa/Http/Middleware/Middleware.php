<?php

declare(strict_types=1);

namespace Eywa\Http\Middleware {

    use Closure;
    use Exception;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\ServerRequest;
    use Eywa\Http\Response\RedirectResponse;
    use Eywa\Http\Response\Response;

    abstract class Middleware
    {
        /**
         *
         * Check the request
         *
         * @param ServerRequest $request
         * @param Closure $next
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        abstract public function handle(ServerRequest $request, Closure $next): Response;

        /**
         *
         * Redirect to the url
         *
         * @param string $url
         * @param int $status
         *
         * @return Response
         *
         * @throws Kedavra
         */
        public function redirect(string $url, int $status = 301): Response
        {
            return (new RedirectResponse($url, $status))->send();
        }
    }
}
