<?php


namespace Eywa\Security\Middleware {


    use Eywa\Http\Middleware\Middleware;
    use Eywa\Http\Request\ServerRequest;
    use Eywa\Http\Response\Response;
    use Eywa\Security\Csrf\Csrf;
    use Eywa\Session\Session;

    class CsrfMiddleware extends Middleware
    {

        /**
         * @inheritDoc
         */
        public function check(ServerRequest $request): Response
        {
            if (cli())
                return $this->next();

            if ($request->submited())
            {
                if ((new Csrf(new Session()))->check())
                    return $this->next();
            }
            return $this->next();
        }
    }
}