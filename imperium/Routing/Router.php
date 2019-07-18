<?php

namespace Imperium\Routing {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
    use Imperium\Middleware\TrailingSlashMiddleware;
    use Imperium\Security\Auth\AuthMiddleware;
    use Imperium\Security\Csrf\CsrfMiddleware;
    use Psr\Http\Message\ServerRequestInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;


    /**
     *
     * Router management
     *
     * @author Willy Micieli <micieli@laposte.net>
     *
     * @package imperium
     *
     * @version 4
     *
     * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE
     *
     **/
    class Router
    {

        use Route;

        /**
         * @var string
         */
        private $method;

        /**
         * @var string
         */
        private $url;

        /**
         *
         */
        private $route;

        /**
         * @var array
         */
        private $args;
        /**
         * @var array
         */
        private $regex;

        /**
         * Router constructor.
         * @param ServerRequestInterface $request
         * @throws Kedavra
         */
        public function __construct(ServerRequestInterface $request)
        {

            $this->method = $request->getMethod() !== GET ?  strtoupper(collection($request->getParsedBody())->get('method')) : GET;

            $this->url           = $request->getUri()->getPath();

            $this->call_middleware($request);
        }

        /**
         *
         * Call the callable
         *
         * @return RouteResult| RedirectResponse
         *
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function search()
        {

            foreach($this->routes()->where('method',EQUAL,$this->method)->get() as $route)
            {
                if ($this->match($route->url))
                {
                    $this->route = $route;
                    return $this->result();
                }

            }
            return to(route('404'));
        }

        /**
         * @param $param
         * @param $regex
         *
         * @return Router
         *
         */
        public function with($param, $regex): Router
        {
            $this->regex[$param] = str_replace('(', '(?:', $regex);

            return $this;
        }

        /**
         *
         * Display a route url by this name
         *
         * @param string $name
         *
         * @param array $args
         *
         * @return string
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public static function url(string $name,array $args=[]): string
        {
            $x = route($name,$args);

            $host = request()->getHost();

            return php_sapi_name() !== 'cli' ? https() ?  "https://$host/$x" : "http://$host/$x" : $x;

        }

        /**
         *
         * @param ServerRequestInterface $request
         *
         * @throws Kedavra
         *
         */
        private function call_middleware(ServerRequestInterface $request): void
        {
            $middleware_dir = 'Middleware';

            $namespace = 'Shaolin' . '\\' . $middleware_dir. '\\';

            $dir = CORE .DIRECTORY_SEPARATOR . $middleware_dir;

            is_false(Dir::is($dir),true,"The $dir directory was not found");

            $middle = glob("$dir/*php");

            call_user_func_array(new CsrfMiddleware(), [$request]);

            call_user_func_array(new TrailingSlashMiddleware(), [$request]);

            call_user_func_array(new AuthMiddleware(), [$request]);

            foreach  ($middle as $middleware)
            {
                $middle = collection(explode(DIRECTORY_SEPARATOR,$middleware))->last();

                $middleware = collection(explode('.',$middle))->begin();

                $class = "$namespace$middleware";

                call_user_func_array(new $class(), [$request]);
            }
        }
        /**
         *
         * @param $match
         *
         * @return string
         *
         */
        private function paramMatch($match):string
        {
           return def($this->regex[$match[1]]) ?  '(' . $this->regex[$match[1]] . ')' :  '([^/]+)';
        }

        /**
         *
         * @return RouteResult
         *
         *
         */
        public function result(): RouteResult
        {
            array_shift($this->args);

            $params = collection();

            foreach ($this->args as $match)
            {
                is_numeric($match) ? $this->with($match,NUMERIC) : $this->with($match,STRING);

                is_numeric($match) ? $params->add(intval($match)): $params->add($match);
            }


           return  new RouteResult(CONTROLLERS_NAMESPACE,$this->route->name,$this->route->url,$this->route->controller,$this->route->action,$params->collection());
        }


        /**
         *
         * Check if a route matches
         *
         * @param string $url
         *
         * @return bool
         *
         */
        private function match(string $url): bool
        {
            $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $url);

            $regex = "#^$path$#";

            return preg_match($regex,$this->url,$this->args) === 1;
        }

    }
}
