<?php

namespace Imperium\Route {

    use Exception;

    /**
     *
     * Route management
     *
     * @author Willy Micieli <micieli@laposte.net>
     *
     * @package imperium
     *
     * @version 4
     *
     * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE
     *
     **/
    class Route
    {
        /**
         *
         * The post method key
         *
         * @var string
         *
         */
        const METHOD_POST = 'POST';

        /**
         *
         * The get method key
         *
         * @var string
         */
        const METHOD_GET = 'GET';

        /**
         *
         * All methods supported
         *
         * @var array
         *
         */
        const METHOD_SUPPORTED = [
            self::METHOD_GET,
            self::METHOD_POST
        ];

        /**
         *
         * The regex for numeric match
         *
         * @var string
         *
         */
        const NUMERIC = '([\d]+)';

        /**
         *
         * The regex for not numeric match
         *
         * @var string
         *
         */
        const NOT_NUMERIC = '([\D])';

        /**
         *
         * The regex for string math
         *
         * @var string
         *
         */
        const STRING = '([a-z]+)';

        /**
         *
         * The regex for alpha numeric math
         *
         * @var string
         *
         */
        const ALPHANUMERIC = '([\w]+)';

        /**
         *
         * The regex for not string match
         *
         * @var string
         *
         */
        const NOT_STRING = '([\W])';

        /**
         *
         * The url route index
         *
         * @var int
         *
         */
        const URL_INDEX = 1;

        /**
         *
         * The route callable index
         *
         * @var int
         *
         */
        const CALLABLE_INDEX = 2;

        /**
         * The route name index
         *
         * @var int
         *
         */
        const NAME_INDEX = 3;

        /**
         *
         * The separator used to determine the controller and the action
         *
         * @var string
         *
         */
        const MVC_SEPARATOR = '@';

        /**
         *
         * The url
         *
         * @var string
         *
         */
        private $url;

        /**
         *
         * The url params
         *
         * @var array
         *
         */
        private $params = [];

        /**
         *
         * All routes
         *
         * @var array
         *
         */
        private static $routes = [];

        /***
         * @var array
         */
        private $matches = [];

        /**
         * @var array
         */
        private static $get_named = [];

        /**
         *
         * @var array
         */
        private static $post_named = [];

        /**
         * @var string
         */
        private $namespace;


        /**
         *
         * The root route
         *
         * @param mixed $callable
         * @param string $name
         *
         * @return Route
         *
         * @throws Exception
         *
         */
        public static function root($callable,$name = 'home'): Route
        {
            self::check($name,self::METHOD_GET);

            self::$get_named[] = $name;

            return self::route('/',$callable,$name,self::METHOD_GET);
        }

        /**
         *
         * Return the route url by use it's name
         *
         * @param string $name The route name
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public static function url(string $name): string
        {
            foreach (self::$routes[self::method()] as $route)
            {
               if (equal($route[self::NAME_INDEX],$name))
                  return $route[self::URL_INDEX];

            }
            throw new Exception('Url was not found');
        }

        /**
         *
         * Return the route callable
         *
         * @param string $name
         *
         * @return callable
         *
         * @throws Exception
         *
         */
        public static function callback(string $name): callable
        {
            foreach (self::$routes[self::method()] as $route)
            {
                if (equal($route[self::NAME_INDEX],$name))
                     return $route[self::CALLABLE_INDEX];
            }
            throw new Exception('Callback was not found');
        }
        /**
         *
         * A route with the get method
         *
         * @param string $url
         * @param $callable
         * @param $name
         *
         * @return Route
         *
         * @throws Exception
         *
         */
        public static function get(string $url,$callable,$name): Route
        {
            self::check($name,self::METHOD_GET);

            self::$get_named[] = $name;

            return self::route($url,$callable,$name,self::METHOD_GET);
        }

        /**
         *
         * A route with the post method
         *
         * @param string $url
         * @param $callable
         * @param string $name
         *
         * @return Route
         *
         * @throws Exception
         *
         */
        public static function post(string $url,$callable,string $name): Route
        {
            self::check($name,self::METHOD_POST);

            self::$post_named[] = $name;

            return self::route($url,$callable,$name,self::METHOD_POST);
        }

        /**
         * @return string
         *
         * @throws Exception
         *
         */
        private static function method(): string
        {
            $x = server('REQUEST_METHOD');

            is_true(not_def($x),true,"No found method");

            return $x;
        }

        /**
         *
         * @param string $name
         * @param string $method
         *
         * @throws Exception
         */
        private static function check(string $name, string $method)
        {
            $get = collection(self::$get_named);
            $post = collection(self::$post_named);

            switch ($method)
            {
                case self::METHOD_GET:
                  is_true($get->exist($name),true,"The route name $name are already use by an another route");
                break;
                case self::METHOD_POST:
                    is_true($post->exist($name),true,"The route name $name are already use by an another route");
                break;
            }

        }

        /**
         *
         * Call the callable
         *
         * @return mixed
         *
         * @throws Exception
         */
        protected  function launch()
        {
            foreach(self::routes(self::method()) as $route)
            {
                $x = collection($route);

                $url = $x->get(self::URL_INDEX);
                $callable = $x->get(self::CALLABLE_INDEX);

               if ($this->match($url))
                    return $this->call($callable);

            }
            throw new Exception('No routes match');
        }


        /**
         *
         * Add a constraint for a param
         *
         * @param string $param
         * @param string $regex
         *
         * @return Route
         *
         */
        public function with(string $param,string $regex): Route
        {
            $this->params[$param] = $regex;

            return $this;
        }

        /**
         *
         *
         * @param string $url
         *
         * @return Route
         *
         * @throws Exception
         *
         */
        protected function capture(string $url): Route
        {
            $this->url = equal($url,'/') ? $url : trim($url,'/');

            return $this;

        }

        /**
         *
         * Save the app namespace
         *
         * @param string $namespace
         *
         * @return Route
         *
         */
        public function namespace(string $namespace): Route
        {
            $this->namespace = $namespace;

            return $this;
        }

        /**
         *
         * Check if a route matches
         *
         * @param string $url
         *
         * @return bool
         *
         */
        private function match(string $url): bool
        {
            $path = preg_replace_callback("#:([\w]+)#",[$this,'matches'],$url);

            $regex = "#^$path$#";
            if (preg_match($regex,$this->url,$matches))
            {
                array_shift($matches);
                $this->matches = $matches;
                return true;
            }
            return false;
        }

        /**
         *
         * @param $callable
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        private function call($callable)
        {
            if(is_string($callable))
            {
                $params = collection(explode(self::MVC_SEPARATOR, $callable));

                $controller = $params->get(0);
                $action     = $params->get(1);


                is_true(not_def($controller,true,"No found the controller"));

                is_true(not_def($action,true,"No found the action"));

                $controller = $this->namespace .'\\' .$controller;

                is_false(class_exists($controller),true,"The class {$params->get(0)} not exist");

                $controller = new $controller();

                is_false(method_exists($controller,$action),true,"The method $action was not found inside the controller named {$params->get(0)}");

                return call_user_func_array([$controller, $action], $this->matches);
            }
            return call_user_func_array($callable, $this->matches);
        }

        /**
         *
         * @param $x
         *
         * @return string
         *
         */
        private function matches($x): string
        {
          return isset($this->params[$x[1]]) ? '(' . $this->params[$x[1]] . ')' :'([^/]+)';
        }


        /**
         *
         * Register a route
         *
         * @param string $url
         * @param $callable
         * @param string $name
         * @param string $method
         *
         * @return Route
         *
         * @throws Exception
         *
         */
        private static function route(string $url,$callable,string $name,string $method): Route
        {
            self::$routes[$method][] = [self::URL_INDEX => $url,self::CALLABLE_INDEX => $callable,self::NAME_INDEX => $name];

            return new static();
        }

        /**
         *
         * Get all routes of a method
         *
         * @param string $method
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public static function routes(string $method): array
        {
            not_in(self::METHOD_SUPPORTED,$method,true,"The method is not supported");

            return self::$routes[$method];
        }

    }
}