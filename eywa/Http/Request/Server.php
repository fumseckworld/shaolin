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
        private Request $request;

        /**
         *
         * Server constructor.
         *
         * @param string $url
         * @param string $method
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $url,string $method = GET)
        {
            not_in(METHOD_SUPPORTED,$method,true,"The method used is not supported");

            $this->url = $url;

            $this->request = php_sapi_name() == 'cli' ? new Request() : Request::generate();

            if (php_sapi_name() !== 'cli')
                $this->method = different($method,GET) ? $this->request->request()->get('_method',GET) : $method;
            else
                $this->method = $method;


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
            return new static($_SERVER['REQUEST_URI'],$_SERVER['REQUEST_METHOD']);
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