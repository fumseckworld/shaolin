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
            if (not_cli()) {
                is_false($request->local(), true, "You must be in localhost");
            }
        }
    }
}
