<?php

declare(strict_types=1);

namespace Eywa\Http\Routing {


    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\Response;
    use ReflectionClass;
    use ReflectionException;

    class RouteResult
    {
        /**
         *
         * The controller namespace
         *
         */
        private string $namespace ;


        /**
         *
         * The route controller
         *
         */
        private string $controller ;

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
         * @var array<mixed>
         *
         */
        private array $args;

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
         * @param array<mixed> $args
         * @param string $method
         *
         */
        public function __construct(string $controller, string $namespace, string $action, array $args, string $method)
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
            return  $this->namespace() . '\\' . $this->controller();
        }

        /**
         * @return Response
         * @throws Kedavra
         * @throws ReflectionException
         */
        public function call(): Response
        {
            is_false(
                class_exists(
                    $this->class()
                ),
                true,
                sprintf(
                    'The class %s not exist inside the Controllers directory',
                    $this->controller()
                )
            );

            is_false(
                method_exists(
                    $this->class(),
                    $this->action()
                ),
                true,
                sprintf(
                    'The action %s not exist inside the %s controller',
                    $this->action(),
                    $this->controller()
                )
            );

            $x = new ReflectionClass($this->class());


            $request = cli() ? new Request([], [], [], [], [], $this->args()) : Request::make($this->args());

            $x->getMethod('before')->invoke($x->newInstance(), $request);

            $result = $x->getMethod($this->action())->invoke($x->newInstance(), $request);


            $x->getMethod('after')->invoke($x->newInstance(), $request);

            return $result;
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
         * @return array<mixed>
         *
         */
        public function args(): array
        {
            return $this->args;
        }
    }
}
