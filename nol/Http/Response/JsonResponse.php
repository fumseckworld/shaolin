<?php

namespace Nol\Http\Response {
    /**
     *
     * Send a json response
     *
     * @author  Willy Micieli <micieli@outlook.fr>
     * @package Imperium\Http\Response\JsonResponse
     * @version 12
     *
     * @property array $data The data to use.
     *
     */
    class JsonResponse
    {
        /**
         *
         * @param array $data The data to use.
         *
         */
        public function __construct(array $data)
        {
            $this->data = $data;
        }

        /**
         *
         * Send the json response
         *
         * @return Response
         *
         */
        public function send(): Response
        {
            return (new Response())
                ->setContent(strval(json_encode($this->data, JSON_FORCE_OBJECT)))
                ->setHeaders(['Content-Type' => 'application/json; charset=UTF-8'])
                ->send();
        }
    }
}
