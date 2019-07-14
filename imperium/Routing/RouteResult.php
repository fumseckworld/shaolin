<?php


namespace Imperium\Routing {


    use Imperium\Exception\Kedavra;
    use Symfony\Component\DependencyInjection\Tests\Compiler\D;
    use Symfony\Component\HttpFoundation\Response;

    class RouteResult
    {
        /**
         * @var string
         */
        private $name;
        /**
         * @var string
         */
        private $url;
        /**
         * @var string
         */
        private $controller;
        /**
         * @var string
         */
        private $action;
        /**
         * @var array
         */
        private $args = [];

        /**
         * @var string
         */
        private $namespace;
        /**
         * @var string
         */
        private $controller_dir;

        /**
         * @var string
         */
        private $class;

        /**
         * RouteResult constructor.
         *
         * @param string $namespace
         * @param string $name
         * @param string $url
         * @param string $controller
         * @param string $action
         * @param array $args
         *
         *
         */
        public function __construct(string $namespace,string $name, string $url, string $controller, string $action,array $args = [])
        {
            $this->name = $name;
            $this->url = $url;
            $this->controller = $controller;
            $this->action = $action;
            $this->args = $args;
            $this->namespace = $namespace;
            $this->class = $this->controller_class();
            $this->controller_dir = CONTROLLERS;


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
            return $this->namespace;
        }

        /**
         *
         * Get the class with namespace
         *
         * @return string
         *
         */
        public function controller_class(): string
        {
            return  "{$this->namespace()}{$this->controller()}";
        }

        /**
         *
         * Get the match route name
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
         * Get the match url
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
         * Get the match controller
         *
         * @return string
         *
         */
        public function controller(): string
        {
            return $this->controller;
        }

        /**
         *
         * Get the match action
         *
         * @return string
         *
         */
        public function action(): string
        {
            return $this->action;
        }

        /**
         *
         * Get the controller dir
         *
         * @return string
         *
         */
        public function controller_dir(): string
        {
            return $this->controller_dir;
        }

        /**
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function call(): Response
        {

            $class = new $this->class();


            is_false(class_exists($this->controller_class()),true,"The class {$this->controller()} not exist at {$this->controller_dir()}");

            is_false(method_exists($this->controller_class(),$this->action()),true,"The action {$this->action()} not exist in {$this->controller()} controller");


            if (method_exists($class, BEFORE_ACTION))
                call_user_func_array( [$class,BEFORE_ACTION],$this->args);

            if (method_exists($class, AFTER_ACTION))
            {
                $x =  call_user_func_array([$class, $this->action()], $this->args);
                call_user_func_array( [$class, AFTER_ACTION],$this->args);

                return (new Response())->setContent($x);
            }

            $x =  call_user_func_array([$class, $this->action()], $this->args);
            return (new Response())->setContent($x);
        }
    }
}