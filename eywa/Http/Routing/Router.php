<?php

declare(strict_types=1);

namespace Eywa\Http\Routing {

    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\ServerRequest;
    use Eywa\Http\Response\RedirectResponse;
    use Eywa\Http\Response\Response;
    use Eywa\Security\Middleware\CsrfMiddleware;
    use stdClass;

    class Router
    {
        /**
         * The request url
         *
         *
         */
        private string $url;

        /**
         *
         * The request method
         *
         */
        private string $method = GET;


        /**
         *
         * The routes parameters
         *
         */
        private array $parameters = [];

        /**
         *
         * All routes params
         *
         */
        private ?stdClass $route = null;


        /**
         *
         * Router constructor.
         *
         * @param ServerRequest $request
         *
         * @throws Kedavra
         *
         */
        public function __construct(ServerRequest $request)
        {
            $this->url = $request->url();

            $this->method = $request->method();

            $this->call_middleware($request);
        }

        /**
         *
         * Call the callable
         *
         * @return Response
         *
         * @throws Kedavra
         */
        public function run(): Response
        {
            foreach (Web::where('method', EQUAL, $this->method)->execute() as $route)
            {
                if ($this->match($route->url,$route))
                {
                    $this->route = $route;

                    return  $this->result()->call();
                }
            }

            return $this->not_found();
        }


        /**
         * @return RouteResult
         */
        public function result(): RouteResult
        {
            return new RouteResult($this->route->controller,$this->route->directory,$this->route->action,$this->parameters,$this->method);
        }

        /**
         *
         * Check if a route matches
         *
         * @param string $url
         *
         * @param $route
         * @return bool
         */
        private function match(string $url,$route): bool
        {

            $path = preg_replace('#:([\w]+)#','([^/]+)',$url);

            $regex = "#^$path$#";

            if(preg_match($regex, $this->url, $this->parameters) === 1)
            {

                $url = $route->url;


                if (def(strstr($url,':')))
                {
                    $args = substr($url,strpos($url,':'));

                    $args = collect(explode(':',$args))->for(function ($x){
                        return trim($x,'/');
                    })->shift()->all();

                    $result = collect();


                    array_shift($this->parameters);

                    foreach ($this->parameters as $k => $v)
                        $result->put($args[$k],$v);

                    $this->parameters = $result->all();

                    return true;
                }
                array_shift($this->parameters);

                return true;
            }
            return false;
        }

        /**
         *
         * Return 404 page
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        private function not_found(): Response
        {
            return (new RedirectResponse(route('web', ['404'])))->send();
        }

        /**
         *
         * Call all middleware
         *
         * @param ServerRequest $request
         *
         * @return void
         *
         * @throws Kedavra
         */
        private function call_middleware(ServerRequest $request): void
        {
            $middleware_dir = 'Middleware';

            $namespace = 'App' . '\\' . $middleware_dir . '\\';

            $dir = base('app'). DIRECTORY_SEPARATOR . $middleware_dir;

            is_false(is_dir($dir), true, "The $dir directory was not found");

            $middle = glob("$dir/*php");

            call_user_func_array([ new CsrfMiddleware(), 'check' ], [ $request ]);

            foreach($middle as $middleware)
            {
                $middle = collect(explode(DIRECTORY_SEPARATOR, $middleware))->last();

                $middleware = collect(explode('.', $middle))->first();

                $class = "$namespace$middleware";

                call_user_func_array([ new $class(), 'check' ], [ $request ]);
            }
        }
    }
}