<?php

namespace Imperium\Router {

    use Exception;
    use Imperium\Collection\Collection;

    /**
     *
     * Router management
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
    class Router
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
        const NUMERIC = '([0-9]+)';

        /**
         *
         * The regex for not numeric match
         *
         * @var string
         *
         */
        const NOT_NUMERIC = '([^0-9]+)';

        /**
         *
         * The regex for alpha numeric math
         *
         * @var string
         *
         */
        const ALPHANUMERIC = '([0-9A-Za-z]+)';

        /**
         *
         * The regex for not string match
         *
         * @var string
         *
         */
        const NOT_STRING = '([^a-zA-Z]+)';

        /**
         *
         * The regex for a slug
         *
         * @var string
         *
         */
        const SLUG  = '([a-zA-Z\-0-9]+)';

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
        private  $url;

        /**
         *
         * The url params
         *
         * @var Collection
         *
         */
        private $params;

        /**
         *
         * All routes
         *
         * @var Collection
         *
         */
        private $routes;

        /***
         *
         * @var array
         */
        private $matches = [];

        /**
         *
         * @var Collection
         *
         */
        private $get_named;

        /**
         *
         * @var Collection
         */
        private $post_named;

        /**
         * @var string
         */
        private $namespace;

        /**
         *
         * The current method used
         *
         * @var string
         *
         */
        private $method;

        /**
         * Router constructor.
         * @param string $url
         * @param string $namespace
         * @param string $method
         */
        public function __construct(string $url,string $namespace,string $method = '')
        {
            $this->method        = def($method) ? $method : server('REQUEST_METHOD');

            $this->post_named    = collection();

            $this->get_named     = collection();

            $this->routes        = collection();

            $this->params        = collection();

            $this->url           = $url;

            $this->namespace = $namespace;
        }

        /**
         *
         * @param string $name
         * @param string $method
         *
         * @throws Exception
         */
        private function check(string $name, string $method)
        {
            switch ($method)
            {
                case self::METHOD_GET:
                  is_true($this->get_named->exist($name),true,"The route name $name are already use by an another route");
                break;
                case self::METHOD_POST:
                    is_true($this->post_named->exist($name),true,"The route name $name are already use by an another route");
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
        public function run()
        {
            foreach($this->routes($this->method) as $route)
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
         * @return Router
         *
         */
        public function with(string $param,string $regex): Router
        {
            $this->params->add($regex,$param);

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
         * @param $x
         * @return string
         */
        private function matches($x): string
        {
            return def($this->params[$x[1]]) ? $this->params[$x[1]] :  '([^/]+)';
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
         * @param bool $with
         * @param array $args
         * @param string[] $regex
         * @return Router
         *
         * @throws Exception
         */
        public function add(string $url,$callable,string $name,string $method,bool $with = false,array $args = [],string ...$regex): Router
        {
            $this->check($name,$method);

            equal($method,self::METHOD_GET) ? $this->get_named->add($name) : $this->post_named->add($name);

            $this->routes->double($method,[self::URL_INDEX => $url,self::CALLABLE_INDEX => $callable,self::NAME_INDEX => $name]);
            if ($with)
            {
                different(length($args),length($regex),true,"They are missing values");

                foreach ($args as $k => $v)
                    $this->with($v,$regex[$k]);
            }
            return $this;
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
        public function routes(string $method): array
        {
            not_in(self::METHOD_SUPPORTED,$method,true,"The method is not supported");

            return key_exists($method,$this->routes->collection()) ? $this->routes->get($method) : $this->routes->collection();
        }

    }
}