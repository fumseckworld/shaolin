<?php


declare(strict_types=1);

namespace Eywa\Http\Response {


    use Eywa\Exception\Kedavra;
    use Eywa\Http\View\View;

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
         *
         */
        public function __construct(string $url, int $status = 301)
        {
           $this->response = new Response(new View('redirect','redirect','redurect',compact('url')),$status,['Location' => $url]);
        }


        /**
         *
         *
         * Send the redirect
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