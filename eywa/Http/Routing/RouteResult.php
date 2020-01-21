<?php

declare(strict_types=1);

namespace Eywa\Http\Routing {


    use Eywa\Exception\Kedavra;

    class RouteResult
    {
        /**
         *
         * The controller namespace
         *
         */
        private string $namesapce;

        /**
         *
         * The route name
         *
         */
        private string $name;

        /**
         *
         * The route url
         *
         */
        private string $url;

        /**
         *
         * The route controller
         *
         */
        private string $controller;

        /**
         *
         * The route action
         *
         */
        private string $acton;


        /**
         *
         * All route args
         *
         */
        private array $args = [];

        /**
         *
         * The route method
         *
         */
        private string $method;

        /**
         *
         * RouteResult constructor.
         *
         * @param string $namesapce
         * @param string $name
         * @param string $url
         * @param string $controller
         * @param string $acton
         * @param string $method
         * @param array $args
         */
        public function __construct(string $namesapce, string $name, string $url, string $controller, string $acton,string $method,array $args = [])
        {
            $this->namesapce = $namesapce;
            $this->name = $name;
            $this->url = $url;
            $this->controller = $controller;
            $this->acton = $acton;
            $this->args = $args;
            $this->method = $method;
        }

        /**
         *
         * Get the controller name
         *
         * @return string
         *
         */
        public function name(): string
        {
            return $this->name;
        }
        /**
         *
         * Get the controller name
         *
         * @return string
         *
         */
        public function controller(): string
        {
            return $this->controller;
        }

        /**
         * @return mixed
         */
        public function class()
        {
            return $this->namespace() .'\\' . $this->controller();
        }

        /**
         * @return mixed
         * @throws Kedavra
         */
        public function call()
        {

            $class = $this->class();
            $instance = new $class();

            is_false(class_exists($class), true, "The class {$this->controller()} not exist inside the Controllers directory");

            is_false(method_exists($class, $this->action()), true, "The action {$this->action()} not exist inside the {$this->controller()} controller");

            if(method_exists($class, BEFORE_ACTION))
                call_user_func_array([ $instance, BEFORE_ACTION ], $this->args());

            if(method_exists($class, AFTER_ACTION))
            {
                $x = call_user_func_array([ $instance, $this->action() ], $this->args());
                call_user_func_array([ $instance, AFTER_ACTION ], $this->args());

                return $x;
            }
            return  call_user_func_array([ $instance, $this->action() ], $this->args());

        }

        /**
         *
         * Get the controller name
         *
         * @return string
         *
         */
        public function action(): string
        {
            return $this->acton;
        }

        /**
         *
         *
         * Get the route method
         *
         * @return string
         *
         */
        public function method()
        {
            return $this->method;
        }

        /**
         *
         * Get the controller name
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
         * Get the namespace
         *
         * @return string
         *
         */
         public function namespace(): string
        {
            return $this->namesapce;
        }

        /**
         *
         * Get all route parametter
         *
         * @return array
         *
         */
        public function args(): array
        {
            return $this->args;
        }

    }
}