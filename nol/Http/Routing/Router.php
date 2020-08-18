<?php

namespace Nol\Http\Routing {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Nol\Exception\Kedavra;
    use Nol\Http\Request\ServerRequest;
    use Nol\Http\Response\Response;

    /**
     * Class Router
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Http\Routing
     * @version 12
     *
     * @property string $method The request method.
     * @property string $url    The request url.
     * @property array $args   The request args.
     * @property array $routes All routes found in the base for the method.
     *
     */
    final class Router
    {
        /**
         *
         * Router constructor.
         *
         * @param ServerRequest $request The user's request.
         * @param string $mode The server mode.
         *
         * @throws NotFoundException
         * @throws Exception
         * @throws DependencyException
         *
         */
        final public function __construct(ServerRequest $request, string $mode = 'site')
        {
            $this->url = $request->url();
            $this->method = $request->method();
            $this->args = [];
            switch (strtolower($mode)) {
                case 'admin':
                    $this->routes = app('admin')->from('routes')->where('method', '=', $this->method)->get();
                    break;
                case 'todo':
                    $this->routes = app('task')->from('routes')->where('method', '=', $this->method)->get();
                    break;
                default:
                    $this->routes = app('web')->from('routes')->where('method', '=', $this->method)->get();
                    break;
            }
        }

        /**
         *
         * Execute the route callback
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         * @return Response
         */
        final public function run(): Response
        {
            foreach ($this->routes as $route) {
                if ($this->match($route->url)) {
                    return (new Route($route->controller, $route->action, $this->args))->exec();
                }
            }
            return (new Route(
                app('not-found-controller-name'),
                app('not-found-action-name'),
                $this->args
            ))->exec();
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
        final private function match(string $url): bool
        {
            $path = preg_replace('#:([\w]+)#', '([^/]+)', $url);

            $regex = "#^$path$#";

            return preg_match($regex, $this->url, $this->args) === 1;
        }
    }
}
