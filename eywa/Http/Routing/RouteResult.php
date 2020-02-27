<?php

declare(strict_types=1);

namespace Eywa\Http\Routing {


    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\Response;

    class RouteResult
    {
        /**
         *
         * The controller namespace
         *
         */
        private string $namespace;


        /**
         *
         * The route controller
         *
         */
        private string $controller;

        /**
         *
         * The route action&
         *
         */
        private string $action;


        /**
         *
         * All route args
         *
         */
        private array $args = [];

        /**
         *
         * The http  method used
         *
         */
        private string $method;

        /**
         *
         * RouteResult constructor.
         *
         * @param string $controller
         * @param string $namespace
         * @param string $action
         * @param array $args
         * @param string $method
         *
         */
        public function __construct(string $controller,string $namespace,string $action,array $args,string $method)
        {

            $this->controller = $controller;
            $this->action = $action;
            $this->args = $args;
            $this->method = $method;
            $this->namespace = $namespace;
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
            return  $this->namespace() .'\\' . $this->controller();
        }

        /**
         * @return Response
         * @throws Kedavra
         */
        public function call(): Response
        {
            $class = $this->class();

            $instance = new $class();

            is_false(class_exists($class), true, "The class {$this->controller()} not exist inside the Controllers directory");

            is_false(method_exists($class, $this->action()), true, "The action {$this->action()} not exist inside the {$this->controller()} controller");


            if (equal($this->method,'POST'))
            {
                 cli() ? call_user_func_array([ $instance, 'before_validation' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'before_validation' ], [Request::generate($this->args())]);
                 cli() ? call_user_func_array([ $instance, 'after_validation' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'after_validation' ], [Request::generate($this->args())]);
                 cli() ? call_user_func_array([ $instance, 'before_save' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'before_save' ], [Request::generate($this->args())]);
                 cli() ? call_user_func_array([ $instance, 'before_create' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'before_create' ], [Request::generate($this->args())]);
                 cli() ? call_user_func_array([ $instance, 'after_create' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'after_create' ], [Request::generate($this->args())]);
                 cli() ? call_user_func_array([ $instance, 'after_save' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'after_save' ], [Request::generate($this->args())]);

            }

            if (equal($this->method,'PUT'))
            {
                 cli() ? call_user_func_array([ $instance, 'before_validation' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'before_validation' ], [Request::generate($this->args())]);
                 cli() ? call_user_func_array([ $instance, 'after_validation' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'after_validation' ], [Request::generate($this->args())]);
                 cli() ? call_user_func_array([ $instance, 'before_save' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'before_save' ], [Request::generate($this->args())]);
                 cli() ? call_user_func_array([ $instance, 'before_update' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'before_update' ], [Request::generate($this->args())]);
                 cli() ? call_user_func_array([ $instance, 'after_update' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'after_update' ], [Request::generate($this->args())]);
                 cli() ? call_user_func_array([ $instance, 'after_save' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'after_save' ], [Request::generate($this->args())]);

            }

            if (equal($this->method,'DELETE'))
            {
                cli() ? call_user_func_array([ $instance, 'before_destroy' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'before_destroy' ], [Request::generate($this->args())]);
                cli() ? call_user_func_array([ $instance, 'after_destroy' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'after_destroy' ], [Request::generate($this->args())]);

            }

            if (equal($this->method,'GET'))
            {
                 cli() ? call_user_func_array([ $instance, 'before_action' ], [new Request([],[],[],[],[],[],$this->args())]) :  call_user_func_array([ $instance, 'before_action' ], [Request::generate($this->args())]);
                 cli() ? call_user_func_array([ $instance, 'after_action' ], [new Request([],[],[],[],[],[],$this->args())]) : call_user_func_array([ $instance, 'after_action' ], [Request::generate($this->args())]);

            }

            return  cli() ?  call_user_func_array([ $instance, $this->action() ], [new Request([],[],[],[],[],[],$this->args())]) :  call_user_func_array([ $instance, $this->action() ], [Request::generate($this->args())]);
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
            return $this->action;
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
             $x = $this->namespace;

             return $x !== 'Controllers' ? "\App\Controllers\\$x" : "\App\Controllers";
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