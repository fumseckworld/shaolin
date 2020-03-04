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

            $this->response = new Response(sprintf('<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="0;url=\'%1$s\'" />
        <title>Redirecting to %1$s</title>
    </head>
    <body>
        Redirecting to <a href="%1$s">%1$s</a>.
    </body>
</html>', htmlspecialchars($url, ENT_QUOTES, 'UTF-8')),$url,$status,['Location' => $url]);
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