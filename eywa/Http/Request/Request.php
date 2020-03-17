<?php

declare(strict_types=1);

namespace Eywa\Http\Request {


    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Parameter\Bag;
    use Eywa\Http\Parameter\Uploaded\UploadedFile;
    use Eywa\Http\Response\Response;
    use Eywa\Validate\Validator;

    class Request
    {

        /**
         *
         * $_GET values
         *
         */
        private Bag $query;

        /**
         *
         * $_POST values
         *
         */
        private Bag $request;

        /**
         *
         * The attibute
         *
         */
        private Collect $attribute ;
        /**
         *
         * $_COOKIE values
         *
         */
        private Bag $cookie;

        /**
         *
         * $_FILES value
         *
         */
        private UploadedFile $file;

        /**
         *
         * $_SERVER value
         *
         */
        private Bag $server;

        /**
         *
         * All router args
         *
         */
        private Bag $args;

        /**
         * Request constructor.
         *
         * @param array<mixed> $request
         * @param array<mixed> $query
         * @param array<mixed> $attributes
         * @param array<mixed> $cookies
         * @param array<mixed> $files
         * @param array<mixed> $server
         * @param array<mixed> $router_args
         *
         * @throws Kedavra
         *
         */
        public function __construct(array $request = [], array  $query = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], array $router_args = [])
        {
            $this->initialize($query, $request, $attributes, $cookies, $files, $server, $router_args);
        }

        /**
         * @return bool
         *
         */
        public function secure(): bool
        {
            return cli() ? false: intval($this->server->get('SERVER_PORT')) === 443;
        }

        /**
         *
         * Creates a new request with values from PHP's super globals.
         *
         * @param array<mixed> $args
         *
         * @return Request
         *
         * @throws Kedavra
         *
         */
        public static function make(array $args = []): Request
        {
            return new self($_POST, $_GET, [], $_COOKIE, $_FILES, $_SERVER, $args);
        }

        /**
         *
         * Get $_GET value
         *
         * @return Bag
         *
         */
        public function query(): Bag
        {
            return $this->query;
        }

        /**
         *
         * Check if the request match the validator rules
         *
         * @param Validator $validator
         *
         * @return Response
         *
         */
        public function validate(Validator $validator): Response
        {
            return call_user_func_array([$validator,'validate'], [$this]);
        }

        /**
         *
         * Get routes args
         *
         * @return Bag
         *
         */
        public function args(): Bag
        {
            return $this->args;
        }

        /**
         *
         * Get $_GET value
         *
         * @return Bag
         *
         */
        public function request(): Bag
        {
            return $this->request;
        }

        /**
         *
         * Get $_GET value
         *
         * @return Bag
         *
         */
        public function server(): Bag
        {
            return $this->server;
        }

        /**
         *
         * Get $_GET value
         *
         * @return UploadedFile
         *
         */
        public function file(): UploadedFile
        {
            return $this->file;
        }

        /**
         *
         * Get $_GET value
         *
         * @return Bag
         *
         */
        public function cookie(): Bag
        {
            return $this->cookie;
        }

        /**
         *
         * Get $_GET value
         *
         * @return Collect
         *
         */
        public function attribute(): Collect
        {
            return $this->attribute;
        }


        /**
         * @param array<mixed> $query
         * @param array<mixed> $request
         * @param array<mixed> $attributes
         * @param array<mixed> $cookies
         * @param array<mixed> $files
         * @param array<mixed> $server
         * @param array<mixed> $router_args
         *
         * @throws Kedavra
         */
        private function initialize(array $query, array $request, array $attributes, array $cookies, array $files, array $server, array $router_args):void
        {
            $this->query = new Bag($query);
            $this->request = new Bag($request);
            $this->attribute = collect($attributes);
            $this->cookie = new Bag($cookies);
            $this->file = new UploadedFile($files);
            $this->server = new Bag($server);
            $this->args = new Bag($router_args);
        }

        /**
         * @return mixed
         */
        public function ip()
        {
            if (cli()) {
                return LOCALHOST_IP;
            }
            $ip = $_SERVER['REMOTE_ADDR'];

            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
                foreach ($matches[0] as $xip) {
                    if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                        $ip = $xip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
                $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
            } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
                $ip = $_SERVER['HTTP_X_REAL_IP'];
            }

            return $ip;
        }

        public function local():bool
        {
            return $this->ip() === LOCALHOST_IP;
        }
    }
}
