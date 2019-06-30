<?php

namespace Imperium\Routing {

    use Exception;
    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
    use Imperium\Middleware\TrailingSlashMiddleware;
    use Imperium\Security\Auth\AuthMiddleware;
    use Imperium\Security\Csrf\CsrfMiddleware;
    use Psr\Http\Message\ServerRequestInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Response;


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
         *
         * The regex for numeric match
         *
         * @var string
         *
         */
        const NUMERIC = '([0-9]+)';

        /**
         *
         * The regex for not numeric match
         *
         * @var string
         *
         */
        const NOT_NUMERIC = '([^0-9]+)';

        /**
         *
         * The regex for alpha numeric math
         *
         * @var string
         *
         */
        const ALPHANUMERIC = '([0-9A-Za-z]+)';

        /**
         *
         * The regex for not string match
         *
         * @var string
         *
         */
        const NOT_STRING = '([^a-zA-Z]+)';

        /**
         *
         * @var string
         *
         */
        const STRING = '([a-zA-Z\-]+)';

        /**
         *
         * The regex for a slug
         *
         * @var string
         *
         */
        const SLUG  = '([a-zA-Z\-0-9]+)';

        /**
         *
         * The separator used to determine the controller and the action
         *
         * @var string
         *
         */
        const MVC_SEPARATOR = '@';

        /**
         *
         * The controller method to execute before the action
         *
         * @var string
         *
         */
        const BEFORE_ACTION = 'before_action';

        /**
         *
         * The controller method to execute after the action
         *
         * @var string
         *
         */
        const AFTER_ACTION = 'after_action';


        const METHOD = 'method';


        /**
         *
         * The url
         *
         * @var string
         *
         */
        private  $url;

        /***
         *
         * arguments
         *
         * @var array
         *
         */
        private $args = [];

        /**
         *
         * The controllers namespace
         *
         * @var string
         *
         */
        private $namespace;

        /**
         *
         * The current method used
         *
         * @var string
         *
         */
        private $method;

        /**
         *
         * The application core directory
         *
         * @var string
         *
         */
        private $core_path;

        /**
         *
         * The controllers directory name
         *
         * @var string
         *
         */
        private $controller_dir;

        /**
         *
         * All regex
         *
         * @var array
         *
         */
        private $regex;

        /**
         * @var
         */
        private $route;


        /**
         *
         * Router constructor.
         *
         * @param ServerRequestInterface $request
         *
         * @throws Kedavra
         *
         */
        public function __construct(ServerRequestInterface $request)
        {

            $controller_dir = collection(config('app','dir'))->get('controller');

            $this->core_path = core_path(collection(config('app','dir'))->get('app'));

            $this->namespace = config('app','namespace'). '\\' . $controller_dir. '\\';

            $this->controller_dir  = $this->core_path . DIRECTORY_SEPARATOR . $controller_dir ;

            is_false(Dir::is($this->core_path),true,"The directory {$this->core_path} was not found");

            is_false(Dir::is($this->controller_dir),true,"The directory $controller_dir was not found at {$this->core_path}");

            if (different($request->getMethod(),GET))
                $this->method = strtoupper(collection($request->getParsedBody())->get(self::METHOD));
            else
                $this->method = $request->getMethod();

            $this->create_route_table(); // to be sure

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
         *
         */
        public function search()
        {
            is_true(not_def($this->routes()->all()),true,"The routes table is empty");

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
         * @throws Kedavra
         *
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
            $middleware_dir = collection(config('app','dir'))->get('middleware');

            $namespace = config('app','namespace') . '\\' . $middleware_dir. '\\';

            $dir = $this->core_path .DIRECTORY_SEPARATOR . $middleware_dir;

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
         * @throws Kedavra
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


           return  new RouteResult($this->namespace,$this->route->name,$this->route->url,$this->route->controller,$this->route->action,$params->collection());
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
