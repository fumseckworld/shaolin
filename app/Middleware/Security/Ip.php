<?php

namespace App\Middleware\Security {

    use Eywa\Http\Middleware\Middleware;
    use Eywa\Http\Request\ServerRequest;

    class Ip extends Middleware
    {
    
        /**
         * @inheritDoc
         */
        public function check(ServerRequest $request): void
        {
            // TODO: Implement check() method.
        }
    }
}
