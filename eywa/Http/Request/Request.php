<?php

declare(strict_types=1);

namespace Eywa\Http\Request {


    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Parameter\Bag;
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
        private Bag $file;

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
         * @param array $request
         * @param array $query
         * @param array $attributes
         * @param array $cookies
         * @param array $files
         * @param array $server
         * @param array $router_args
         * @param null $content
         */
        public function __construct(array $request = [], array  $query = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], array $router_args = [],$content = null)
        {
            $this->initialize($query, $request, $attributes, $cookies, $files, $server, $router_args,$content);
        }

        /**
         *
         * The request content
         *
         */
        private $content = null;


        /**
         *
         * Creates a new request with values from PHP's super globals.
         *
         * @param array $args
         *
         * @return Request
         *
         */
        public static function generate(array $args = []): Request
        {
            return new static($_POST,$_GET,[],$_COOKIE,$_FILES,$_SERVER,$args);
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
         * @throws Kedavra
         *
         */
        public function validate(Validator $validator): Response
        {
            return $validator::check($this);
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
         * @return Bag
         *
         */
        public function file(): Bag
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
         * @return null
         */
        public function content()
        {
            return $this->content;
        }


        /**
         * @param array $query
         * @param array $request
         * @param array $attributes
         * @param array $cookies
         * @param array $files
         * @param array $server
         * @param array $router_args
         * @param $content
         */
        private function initialize(array $query, array $request, array $attributes, array $cookies, array $files, array $server, array $router_args, $content)
        {
            $this->query = new Bag($query);
            $this->request = new Bag($request);
            $this->attribute = collect($attributes)->for([$this,'secure']);
            $this->cookie = new Bag($cookies);
            $this->file = new Bag($files);
            $this->server = new Bag($server);
            $this->content = $content;
            $this->args = new Bag($router_args);
        }
    }
}