<?php

declare(strict_types=1);

namespace Eywa\Http\Response {


    use Eywa\Exception\Kedavra;

    class JsonResponse
    {


        /**
         *
         * The redirect url
         *
         */
        private string $url;

        private Response $reponse;

        /**
         *
         *
         * Json response constructor.
         *
         * @param array<mixed> $data
         * @param int $status
         *
         * @throws Kedavra
         *
         */
        public function __construct(array $data, int $status = 200)
        {
            $json = json_encode($data, JSON_FORCE_OBJECT);
            $json = is_bool($json) ? '' : strval($json);
            $this->reponse = new Response($json, '', $status, ['Content-Type' => 'application/json']);
        }

        /**
         *
         * Send the json response
         *
         *
         * @return Response
         *
         */
        public function send(): Response
        {
            return  $this->reponse->send();
        }
    }
}
