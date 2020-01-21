<?php

declare(strict_types=1);
namespace Eywa\Http\Request {


    use Eywa\Exception\Kedavra;

    class Server
    {

        /**
         *
         * The method
         *
         */
        private string $method;

        /**
         *
         * The url
         *
         */
        private string $url;

        /**
         *
         * the request
         *
         */
        private ?Request $request = null;

        /**
         *
         * Server constructor.
         *
         * @param string $url
         * @param string $method
         *
         * @param Request|null $request
         * @throws Kedavra
         */
        public function __construct(string $url,string $method = GET,Request $request = null)
        {
            not_in(METHOD_SUPPORTED,$method,true,"The method used is not supported");

            $this->method = different($method,GET) ? $request->request()->get('_method',GET) : GET;

            $this->url = $url;

            $this->request = $request;
        }


        /**
         *
         * Generate request from global
         *
         * @return Server
         *
         * @throws Kedavra
         *
         */
        public static function generate(): Server
        {
            $request = php_sapi_name() !== 'cli' ? Request::generate() : new Request();
            return new static($request->server()->get('REQUEST_URI','/'),$request->server()->get('REQUEST_METHOD',GET),$request);
        }

        /***
         *
         * Get the server method
         *
         * @return string
         *
         */
        public function method(): string
        {
            return $this->method;
        }

        /**
         *
         * Get the server url
         *
         * @return string
         *
         */
        public function url(): string
        {
            return $this->url;
        }

        /**
         *
         * Get the request
         *
         * @return Request
         *
         */
        public function request():Request
        {
            return $this->request;
        }
    }
}