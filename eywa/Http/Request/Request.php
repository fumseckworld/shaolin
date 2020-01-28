<?php


namespace Eywa\Http\Request {


    use Eywa\Collection\Collect;
    use Eywa\Http\Parameter\Bag;
    use Eywa\Http\Response\Response;
    use Eywa\Security\Validator\Validator;


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
         * The request content
         *
         */
        private $content = null;

        /**
         * Request constructor.
         *
         * @param array $query
         * @param array $request
         * @param array $attributes
         * @param array $cookies
         * @param array $files
         * @param array $server
         * @param null $content
         */
        public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
        {
            $this->initialize($query, $request, $attributes, $cookies, $files, $server, $content);
        }


        /**
         *
         * Creates a new request with values from PHP's super globals.
         *
         * @return Request
         *
         */
        public static function generate(): Request
        {
            return new static($_GET,$_POST,[],$_COOKIE,$_FILES,$_SERVER);
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



        private function initialize(array $query, array $request, array $attributes, array $cookies, array $files, array $server, $content)
        {
            $this->query = new Bag($query);
            $this->request = new Bag($request);
            $this->attribute = collect($attributes)->for([$this,'secure']);
            $this->cookie = new Bag($cookies);
            $this->file = new Bag($files);
            $this->server = new Bag($server);
            $this->content = $content;
        }
    }
}