<?php

declare(strict_types=1);

namespace Eywa\Http\Routing {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Exception\Kedavra;
    use Psr\Http\Message\ServerRequestInterface;
    use stdClass;
    use Symfony\Component\HttpFoundation\RedirectResponse;

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
         * The regex
         *
         */
        private string $regex;

        /**
         *
         * The route found
     * aza0aaaa
         *
         */
        private stdClass $route;

        /**
         *
         * The routes parameters
         *
         */
        private array $parameters = [];

        /**
         *
         * Router constructor.
         *
         * @param ServerRequestInterface $request
         *
         */
        public function __construct(ServerRequestInterface $request)
        {
            $this->url = $request->getUri()->getPath();
            $this->method = $request->getMethod() !== GET ? def($request->getParsedBody()) ? strtoupper(collect($request->getParsedBody())->get('_method')) : $request->getMethod() : GET;
        }

        /**
         *
         * Call the callable
         *
         * @return RouteResult | RedirectResponse
         *
         * @throws DependencyException
         * @throws NotFoundException*@throws \DI\DependencyException
         * @throws Kedavra
         * @throws DependencyException
         */
        public function search()
        {

            if (equal(config('mode', 'mode'), 'admin'))
            {

                foreach(Admin::where('method', EQUAL, $this->method)->all() as $route)
                {

                    if($this->match($route->url))
                    {
                        $this->route = $route;

                        return $this->result();
                    }
                }

            }

            if (equal(config('mode', 'mode'), 'todo'))
            {

                foreach (Task::where('method',EQUAL,$this->method)->all() as $route)
                {
                    if($this->match($route->url))
                    {
                        $this->route = $route;

                        return $this->result();
                    }
                }
            }

            if (equal(config('mode','mode'),'up'))
            {
                foreach(Web::where('method', EQUAL, $this->method)->all() as $route)
                {
                    if($this->match($route->url))
                    {
                        $this->route = $route;

                        return $this->result();
                    }
                }
            }

            return to(route('web','404'));
        }

        /**
         * @param $param
         * @param $regex
         *
         * @return Router
         *
         */
        public function with($param, $regex) : Router
        {

            $this->regex[ $param ] = str_replace('(', '(?:', $regex);

            return $this;
        }

        /**
         *
         * @param  ServerRequestInterface  $request
         *
         * @throws Kedavra
         *
         */
        private function call_middleware(ServerRequestInterface $request) : void
        {

            $middleware_dir = 'Middleware';

            $namespace = 'App' . '\\' . $middleware_dir . '\\';

            $dir = base('app'). DIRECTORY_SEPARATOR . $middleware_dir;

            is_false(is_dir($dir), true, "The $dir directory was not found");

            $middle = glob("$dir/*php");

            call_user_func_array([ new CsrfMiddleware(), 'handle' ], [ $request ]);

            foreach($middle as $middleware)
            {
                $middle = collect(explode(DIRECTORY_SEPARATOR, $middleware))->last();

                $middleware = collect(explode('.', $middle))->first();

                $class = "$namespace$middleware";

                call_user_func_array([ new $class(), 'handle' ], [ $request ]);
            }
        }

        /**
         *
         * @param $match
         *
         * @return string
         *
         */
        private function paramMatch($match) : string
        {

            return def($this->regex[ $match[ 1 ] ]) ? '(' . $this->regex[ $match[ 1 ] ] . ')' : '([^/]+)';
        }

        /**
         *
         * @return RouteResult
         *
         *
         */
        public function result() : RouteResult
        {

            array_shift($this->parameters);

            $params = collect();

            foreach($this->parameters as $match)
            {
                is_numeric($match) ? $this->with($match, NUMERIC) : $this->with($match, STRING);
                is_numeric($match) ? $params->set(intval($match)) : $params->set($match);
            }

            return new RouteResult('App\Controllers', $this->route->name, $this->route->url, $this->route->controller, $this->route->action, $params->all());
        }

        /**
         *
         * Check if a route matches
         *
         * @param  string  $url
         *
         * @return bool
         *
         */
        private function match(string $url) : bool
        {

            $path = preg_replace_callback('#:([\w]+)#', [ $this, 'paramMatch' ], $url);

            $regex = "#^$path$#";

            return preg_match($regex, $this->url, $this->parameters) === 1;
        }
    }
}