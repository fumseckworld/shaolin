<?php

namespace Imperium\Routing {

    use Exception;
    use Imperium\Directory\Dir;
    use Imperium\File\File;
    use Imperium\Request\Request;
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
         * The url route index
         *
         * @var int
         *
         */
        const URL_INDEX = 'url';

        /**
         *
         * The route callable index
         *
         * @var int
         *
         */
        const CALLABLE_INDEX = 'call';


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
         * The url
         *
         * @var string
         *
         */
        private static $URL;

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
         * @var array
         */
        private $matches = [];

        /**
         * @var string
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
         * @var string
         */
        private $core_path;

        /**
         * @var string
         */
        private $controller_dir;


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

            $this->method        = $request->getMethod();

            $this->url           = $request->getUri()->getPath();

            self::$URL            = $this->url;

            $this->call_middleware($request);
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
         * Call the callable
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function run()
        {
            if (is_admin())
            {
                foreach(admin($this->method) as $name => $route)
                {
                    $x = collection($route);

                    $url       = $x->get(self::URL_INDEX);
                    $callable  = $x->get(self::CALLABLE_INDEX);

                    if ($this->match($url))
                        return $this->call($callable);
                }
            }else
            {
                foreach(web($this->method) as $name => $route)
                {
                    $x = collection($route);

                    $url       = $x->get(self::URL_INDEX);
                    $callable  = $x->get(self::CALLABLE_INDEX);

                    if ($this->match($url))
                        return $this->call($callable);
                }
            }

            throw new Exception('No routes match');
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

            $path =  preg_replace('#:([\w]+)#', '([^/]+)',$url);

            $regex = "#^$path$#";

            if (preg_match($regex,$this->url,$matches))
            {
                array_shift($matches);
                $this->matches = $matches;
                return true;
            }

            return false;
        }

        /**
         *
         * @param $callable
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        private function call($callable)
        {
            if(is_string($callable))
            {
                $params = collection(explode(self::MVC_SEPARATOR, $callable));

                $controller = $params->get(0);

                $action     = $params->get(1);

                is_true(not_def($controller),true,'The controller was not found use the @ separator');

                is_true(not_def($action),true,'The action was not found use the @ separator');

                $controller = $this->namespace  . $controller;

                is_false(class_exists($controller),true,"The class {$params->get(0)} not exist at {$this->controller_dir}");

                $controller = new $controller();

                is_false(method_exists($controller,$action),true,"The  $action method  was not found inside the controller named {$params->get(0)} at {$this->controller_dir}");

                return call_user_func_array([$controller, $action], $this->matches);
            }
            return call_user_func_array($callable, $this->matches);
        }

        /**
         *
         * Get the url route by its name
         *
         * @param string $route_name
         * @param string $method The route method
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public static function web(string $route_name,$method = GET): string
        {
            foreach(web($method) as $name => $route)
            {
                $x = collection($route);

                $url       = $x->get(self::URL_INDEX);

                if (different(php_sapi_name(),'cli'))
                {
                    if (equal($route_name,$name))
                        return https() ? 'https://'  . trim(Request::request()->server->get('HTTP_HOST'),'/') . $url : 'http://' . trim(Request::request()->server->get('HTTP_HOST'),'/') . $url ;
                }else
                {
                    if (equal($route_name,$name))
                        return $url;
                }
            }

            throw new Exception("We have not found an url with the $route_name name");
        }
        /**
         *
         * Get the url route by its name
         *
         * @param string $route_name
         * @param string $method The route method
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public static function admin(string $route_name,$method = GET): string
        {

            foreach(admin($method) as $name => $route)
            {
                $x = collection($route);

                $url       = $x->get(self::URL_INDEX);

                if (different(php_sapi_name(),'cli'))
                {
                    if (equal($route_name,$name))
                        return https() ? 'https://'  . trim(Request::request()->server->get('HTTP_HOST'),'/') . $url : 'http://' . trim(Request::request()->server->get('HTTP_HOST'),'/') . $url ;
                }else
                {
                    if (equal($route_name,$name))
                        return $url;
                }
            }

            throw new Exception("We have not found an url with the $route_name name");
        }

        /**
         *
         * Get the route callable by the route name
         *
         * @param string $route_name
         * @param string $method The route method
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public static function callback(string $route_name,string $method = GET): string
        {
            if (is_admin())
            {
                foreach(admin($method) as $name => $route)
                {
                    $x = collection($route);

                    $callback       = $x->get(self::CALLABLE_INDEX);

                    if (equal($name,$route_name))
                        return $callback;
                }
            }else
            {
                foreach(web($method) as $name => $route)
                {
                    $x = collection($route);


                    $callback       = $x->get(self::CALLABLE_INDEX);

                    if (equal($name,$route_name))
                        return $callback;
                }
            }
            throw new Exception('The callback was not found');
        }


    }
}