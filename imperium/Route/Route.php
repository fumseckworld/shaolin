<?php

namespace Imperium\Route {

    use Exception;


    class Route
    {

        const POST = 'POST';

        const GET = 'GET';

        const SEPARATOR = '@';


        /**
         * @var string
         */
        private $url;

        /**
         * @var
         */
        private static $matches;

        /**
         * @var \Imperium\Collection\Collection
         */
        private $name;


        /**
         * @var string
         */
        private $method;


        /**
         * @var array
         */
        private static $urls = [];


        /**
         * Route constructor.
         *
         * @param string $url
         */

        public function __construct(string $url)
        {
            $this->url = trim($url, '/');
        }

        /**
         * @param string $url
         *
         * @return Route
         *
         */
        public static function capture(string $url): Route
        {
            return new static($url);
        }

        /**
         * @return mixed
         *
         * @throws Exception
         */
        public function run()
        {
            $method = server('REQUEST_METHOD');
            foreach (self::$urls[$method] as $url => $callable)
            {

                if (self::match($url))
                    return self::call($callable);

            }


            throw new Exception('Route was not found');

        }


        /**
         * Define a route with method get
         *
         * @param string $url
         * @param string $callable
         *
         */
        public static function get(string $url, string $callable)
        {

            self::$urls[self::GET] = [$url => $callable];


        }

        /**
         *
         * Define a route with method post
         *
         * @param string $url
         * @param string $callable
         *
         */
        public static function post(string $url, string $callable)
        {

            self::$urls[self::POST] = [$url => $callable];


        }

        /**
         *
         * Define the root route
         *
         * @param string $callable
         *
         */
        public static function root(string $callable)
        {
            self::$urls['ROOT'] = [ '/' => $callable];

        }


        /**
         *
         * @param string $url
         *
         * @return bool
         *
         * @throws Exception
         */
        public static function match(string $url): bool
        {


            $x = preg_replace('#:([\w]+)#', '([^/]+)', self::$urls);



            $regex = "#^$x$#i";


            if (is_true(preg_match($regex, $url, $matches)))
            {
                array_shift($matches);
                self::$matches = $matches;
                return true;
            }

            return false;
        }

        private static function call(string $callable)
        {

            $x = collection(explode(self::SEPARATOR, $callable));
            $controller = $x->get(0);
            $controller = new $controller();
            return call_user_func_array([$controller, $x->get(1)], self::$matches);

        }

        /**
         *
         * Return the route method
         *
         * @return string
         *
         */
        public function method(): string
        {
            return $this->method;
        }

        /**
         * Add a name to the route
         *
         * @param string $name
         *
         * @return Route
         *
         */
        public function name(string $name): Route
        {
            $this->name->add($name, $this->method());

            return $this;
        }
    }
}