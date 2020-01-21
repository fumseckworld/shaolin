<?php

declare(strict_types=1);

namespace Eywa\Http\Routing {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Server;
    use Eywa\Http\Response\RedirectResponse;
    use Eywa\Http\Response\Response;
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
         * The regex
         *
         */
        private string $regex;


        /**
         *
         * The routes parameters
         *
         */
        private array $parameters = [];

        private ?stdClass $route = null;

        /**
         *
         * Router constructor.
         *
         * @param Server $request
         *
         */
        public function __construct(Server $request)
        {
            $this->url = $request->url();
            $this->method = $request->method();
        }

        /**
         *
         * Call the callable
         *

         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         */
        public function run()
        {

            if (equal(config('mode', 'mode'), 'admin'))
            {

                foreach(Admin::where('method', EQUAL, $this->method)->execute() as $route)
                {

                    if($this->match($route->url))
                    {
                        $this->route = $route;

                        return $this->result()->call();
                    }
                }

            }

            if (equal(config('mode', 'mode'), 'todo'))
            {

                foreach (Task::where('method',EQUAL,$this->method)->execute() as $route)
                {
                    if($this->match($route->url))
                    {
                        $this->route = $route;

                        return $this->result()->call();
                    }
                }
            }


            if (equal(config('mode','mode'),'up'))
            {
                foreach(Web::where('method', EQUAL, $this->method)->execute() as $route)
                {
                    if($this->match($route->url))
                    {
                        $this->route = $route;

                        return  $this->result()->call();
                    }

                }

            }

          return  $this->not_found();
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
         * @return RouteResult
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

            return new RouteResult('App\Controllers', $this->route->name, $this->route->url, $this->route->controller, $this->route->action, $this->method, $params->all());
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

        /**
         *
         * Return 404 page
         *
         * @return Response
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        private function not_found(): Response
        {
            return (new RedirectResponse(route('web','404')))->send();
        }
    }
}