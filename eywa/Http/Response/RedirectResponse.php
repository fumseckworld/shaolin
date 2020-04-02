<?php

declare(strict_types=1);

namespace Eywa\Http\Response {

    use Eywa\Exception\Kedavra;

    class RedirectResponse
    {


        /**
         *
         * The redirect response
         *
         */
        private Response $response;

        /**
         *
         * RedirectResponse constructor.
         *
         * @param string $url
         * @param int $status
         *
         * @throws Kedavra
         */
        public function __construct(string $url, int $status = 301)
        {
            $this->response = new Response('', $url, $status, ['Location' => $url]);
        }


        /**
         *
         * Get the response
         *
         * @return Response
         *
         */
        public function get(): Response
        {
            return $this->response;
        }

        /**
         *
         * Send the response
         *
         * @return Response
         *
         */
        public function send(): Response
        {
            return $this->response->send();
        }
    }
}
