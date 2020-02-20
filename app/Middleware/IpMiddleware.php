<?php


namespace App\Middleware {

    use Eywa\Http\Middleware\Middleware;
    use Eywa\Http\Request\ServerRequest;
    use Eywa\Http\Response\Response;

    class IpMiddleware extends Middleware
    {
    
        /**
         * @inheritDoc
         */
        public function check(ServerRequest $request): Response
        {
            if (cli())
                return $this->next();

            is_false($request->local(),true,"You must be in localhost");

            return  $this->next();

        }
    }
}