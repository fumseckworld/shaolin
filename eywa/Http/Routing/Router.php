<?php

declare(strict_types=1);

namespace Eywa\Http\Routing {

    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\ServerRequest;
    use Eywa\Http\Response\RedirectResponse;
    use Eywa\Http\Response\Response;
    use Eywa\Security\Middleware\CsrfMiddleware;
    use ReflectionClass;
    use ReflectionException;
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
         * @var array<mixed>
         *
         */
        private array $parameters = [];

        /**
         *
         * All routes params
         *
         */
        private stdClass $route;


        /**
         *
         * Router constructor.
         *
         * @param ServerRequest $request
         *
         * @throws Kedavra
         * @throws ReflectionException
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
         * @throws ReflectionException
         */
        public function run(): Response
        {
            foreach ((new Sql(connect(SQLITE,base('routes','web.sqlite3')),'routes'))->where('method',EQUAL,$this->method)->get() as $route)
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
         * @param stdClass $route
         *
         * @return bool
         *
         */
        private function match(string $url,stdClass $route): bool
        {

            $path = preg_replace('#:([\w]+)#','([^/]+)',$url);

            $regex = "#^$path$#";

            if(preg_match($regex, $this->url, $this->parameters) === 1)
            {

                $url = $route->url;


                if (def(strstr($url,':')))
                {
                    $args = strval(substr($url,intval(strpos($url,':'))));

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
         * @throws ReflectionException
         *
         */
        private function call_middleware(ServerRequest $request): void
        {
            $middleware_dir = 'Middleware';

            $namespace = 'App' . '\\' . $middleware_dir . '\\';

            $dir = base('app',$middleware_dir);

            is_false(is_dir($dir), true, "The $dir directory was not found");

            $middle = files(base('app',$middleware_dir,'*.php'));

            call_user_func_array([ new CsrfMiddleware(), 'check' ], [ $request ]);

            foreach($middle as $middleware)
            {
                $middle = collect(explode(DIRECTORY_SEPARATOR, $middleware))->last();

                $middleware = collect(explode('.', $middle))->first();

                $class = "$namespace$middleware";

                $x = new ReflectionClass($class);


                $x->getMethod('check')->invoke($x->newInstance(),$request);            }
        }
    }
}