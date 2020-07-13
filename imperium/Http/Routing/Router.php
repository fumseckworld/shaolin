<?php

namespace Imperium\Http\Routing {

    use Imperium\Http\Request\ServerRequest;
    use Imperium\Http\Response\Response;


    /**
     * Class Router
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Http\Routing
     * @version 12
     *
     * @property string $method The request method.
     * @property string $url    The request url.
     * @property array  $args   The request args.
     * @property array  $routes All routes found in the base for the method.
     *
     */
    class Router
    {
        /**
         * Router constructor.
         *
         * @param ServerRequest $request The user's request.
         * @param int           $mode    The server mode.
         */
        public function __construct(ServerRequest $request, int $mode = SITE)
        {
            $this->url = $request->url();
            $this->method = $request->method();
            $this->args = [];
            switch ($mode) {
                case ADMIN:
                    $this->routes = app('admin')->from('routes')->where('method', '=', $this->method)->results();
                    break;
                case TODO:
                    $this->routes = app('task')->from('routes')->where('method', '=', $this->method)->results();
                    break;
                default:
                    $this->routes = app('web')->from('routes')->where('method', '=', $this->method)->results();
                    break;
            }
        }

        /**
         *
         * Call the correct controller method.
         *
         * @return Response
         *
         */
        public function run(): Response
        {
            foreach ($this->routes as $route) {
                if ($this->match($route->url)) {
                    return new  Response();
                }
            }
            return new  Response();
        }

        /**
         *
         * Check if a url match a route.
         *
         * @param string $url The url to check.
         *
         * @return bool
         *
         */
        private function match(string $url): bool
        {
            $path = preg_replace('#:([\w]+)#', '([^/]+)', $url);

            $regex = "#^$path$#";

            return preg_match($regex, $this->url, $this->args) === 1;
        }
    }
}
