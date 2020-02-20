<?php


namespace Eywa\Security\Middleware {


    use Eywa\Http\Middleware\Middleware;
    use Eywa\Http\Request\ServerRequest;
    use Eywa\Security\Csrf\Csrf;
    use Eywa\Session\Session;

    class CsrfMiddleware extends Middleware
    {

        /**
         * @inheritDoc
         */
        public function check(ServerRequest $request): void
        {
            if ( not_cli() && $request->submited())
            {
                (new Csrf(new Session()))->check();
            }
        }
    }
}