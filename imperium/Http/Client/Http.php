<?php

namespace Imperium\Http\Client {

    use Imperium\Http\Response\Response;

    class Http
    {
        /**
         *
         * Visit a page.
         *
         * @param string $url The url to access.
         * @param string $method The request http method.
         *
         * @return Response
         *
         */
        public function visit(string $url, string $method = 'GET'): Response
        {
            return app('response')->from('cli', $url, $method)->get();
        }

        public function see(string $element): bool
        {
            return true;
        }
    }
}
