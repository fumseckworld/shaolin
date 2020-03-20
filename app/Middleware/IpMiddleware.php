<?php


namespace App\Middleware {

    use Eywa\Http\Middleware\Middleware;
    use Eywa\Http\Request\ServerRequest;

    class IpMiddleware extends Middleware
    {
    
        /**
         * @inheritDoc
         */
        public function check(ServerRequest $request): void
        {
        }
    }
}
