<?php

namespace Imperium\Routing {

    use Exception;
    use Imperium\Directory\Dir;
    use Imperium\File\File;
    use Imperium\Middleware\TrailingSlashMiddleware;
    use Imperium\Security\Auth\AuthMiddleware;
    use Imperium\Security\Csrf\CsrfMiddleware;
    use Psr\Http\Message\ServerRequestInterface;


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
        /**
         *
         * The post method key
         *
         * @var string
         *
         */
        const METHOD_POST = 'POST';

        /**
         *
         * The get method key
         *
         * @var string
         */
        const METHOD_GET = 'GET';

        /**
         *
         * All methods supported
         *
         * @var array
         *
         */
        const METHOD_SUPPORTED =  ['DELETE', 'PATCH', 'POST', 'PUT', 'GET'];

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
         * Table to store routes
         *
         * @var string
         *
         */
        const ROUTES = 'routes';


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
         *
         * Router constructor.
         *
         * @param ServerRequestInterface $request
         *
         * @throws Exception
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



            $this->url           = $request->getUri()->getPath();

            $this->call_middleware($request);

        }


        /**
         *
         * Call the callable
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function run()
        {
            is_true(app()->table_not_exist(self::ROUTES),true,"The routes table was not found");

            foreach(app()->model()->from(self::ROUTES)->where('method',EQUAL,$this->method)->get() as $route)
            {
                if ($this->match($route->url))
                    return $this->call($route);
            }
            return to(name('404'));
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
         * @param $name
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function url(string $name): string
        {
            $x = app()->model()->query()->from(self::ROUTES)->where('name',EQUAL,$name)->use_fetch()->get();

            is_true(not_def($x),true,'Route was not found');

            $host = request()->getHost();

            return https() ? "https://$host$x->url" : "http://$host$x->url";

        }

        /**
         *
         * @param ServerRequestInterface $request
         *
         * @throws Exception
         *
         */
        private function call_middleware(ServerRequestInterface $request): void
        {
            $middleware_dir = collection(config('app','dir'))->get('middleware');

            $namespace = config('app','namespace') . '\\' . $middleware_dir. '\\';

            $dir = $this->core_path .DIRECTORY_SEPARATOR . $middleware_dir;

            is_false(Dir::is($dir),true,"The $dir directory was not found");

            $middle = File::search("$dir/*php");

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
            if(isset($this->regex[$match[1]]))
            {
                return '(' . $this->regex[$match[1]] . ')';
            }
            return '([^/]+)';
        }
        /**
         *
         * @param $route
         *
         * @return mixed
         *
         * @throws Exception
         */
        private function call($route)
        {
            array_shift($this->args);

            $params = collection();
            foreach ($this->args as $match)
            {
                if (is_string($match))
                    $params->add($match);
                elseif (is_numeric($match))
                    $params->add(intval($match));
                else
                    $params->add($match);
            }

            $this->args = $params->collection();

            $controller = $route->controller;

            $action     = $route->action;

            $controller = $this->namespace  . $controller;

            is_false(class_exists($controller),true,"The class {$route->controller} not exist at {$this->controller_dir}");

            $controller = new $controller();

            is_false(method_exists($controller,$action),true,"The  $action method  was not found inside the controller named {$route->controller} at {$this->controller_dir}");

            if (method_exists($controller, self::BEFORE_ACTION))
                call_user_func_array( [$controller,self::BEFORE_ACTION],[]);

            if (method_exists($controller, self::AFTER_ACTION))
            {
                $x =  call_user_func_array([$controller, $action], $this->args);
                call_user_func_array( [$controller, self::AFTER_ACTION],[]);
                return $x;
            }
            return call_user_func_array([$controller, $action], $this->args);
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
