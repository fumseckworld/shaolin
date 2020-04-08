<?php

declare(strict_types=1);

namespace Eywa\Http\Request {


    use Eywa\Exception\Kedavra;
    use Eywa\Http\Parameter\Bag;
    use Eywa\Http\Parameter\Uploaded\UploadedFile;

    class ServerRequest
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
         * ServerRequest constructor.
         *
         * @param string $url
         * @param string $method
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $url, string $method = GET)
        {
            not_in(METHOD_SUPPORTED, $method, true, "The method used is not supported");

            $this->url = $url;

            if (not_cli()) {
                $this->request = Request::make();
                $this->method = different($method, GET) ?
                    strval(
                        $this->request->request()->get('_method', POST)
                    )
                    : $method;
            } else {
                $this->request = new Request();

                $this->method = $method;
            }
        }

        /**
         *
         * Check if the server url match the url
         *
         * @param string $url
         *
         * @return bool
         *
         */
        public function match(string $url): bool
        {
            return $this->url() === $url;
        }

        /**
         *
         * Generate request from global
         *
         * @return ServerRequest
         *
         * @throws Kedavra
         *
         */
        public static function generate(): ServerRequest
        {
            return new self($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
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
         * $_POST
         *
         * @return Bag
         *
         */
        public function request(): Bag
        {
            return $this->request->request();
        }

        /**
         *
         * Check if the request is submited by a form
         *
         * @return bool
         *
         */
        public function submited(): bool
        {
            return !in_array($this->method(), [GET]);
        }

        /**
         *
         * Check if the request is submited by a form
         *
         * @return bool
         *
         */
        public function local(): bool
        {
            return $this->request->local();
        }


        /**
         *
         * $_GET
         *
         * @return Bag
         *
         */
        public function query(): Bag
        {
            return $this->request->query();
        }

        /**
         *
         * $_COOKIE
         *
         * @return Bag
         *
         */
        public function cookie(): Bag
        {
            return $this->request->cookie();
        }

        /**
         *
         * $_SERVER
         *
         * @return Bag
         *
         */
        public function server(): Bag
        {
            return $this->request->server();
        }

        /**
         *
         * $_FILES
         *
         * @return UploadedFile
         *
         */
        public function file(): UploadedFile
        {
            return $this->request->file();
        }


        /**
         * @return bool
         */
        public function missingToken(): bool
        {
            return not_def($this->request->request()->get(CSRF_TOKEN));
        }
    }
}
