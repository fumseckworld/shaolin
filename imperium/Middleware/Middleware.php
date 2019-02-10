<?php

namespace Imperium\Middleware {


    use Psr\Http\Message\ServerRequestInterface;

    interface  Middleware
    {
        /**
         * @param ServerRequestInterface $request
         * @return mixed
         */
        public function __invoke(ServerRequestInterface $request);
    }
}