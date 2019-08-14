<?php
	
	namespace Imperium\Routing
	{
		
		use DI\DependencyException;
		use DI\NotFoundException;
		use Imperium\Directory\Dir;
		use Imperium\Exception\Kedavra;
		use Imperium\Middleware\TrailingSlashMiddleware;
		use Imperium\Model\Routes;
		use Imperium\Security\Auth\AuthMiddleware;
		use Imperium\Security\Csrf\CsrfMiddleware;
		use Psr\Http\Message\ServerRequestInterface;
		use Symfony\Component\HttpFoundation\RedirectResponse;
		
		/**
		 * Class Router
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Routing
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Router
		{
			
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
			 *
			 * @param  ServerRequestInterface  $request
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 */
			public function __construct(ServerRequestInterface $request)
			{
				
				$this->method = $request->getMethod() !== GET ? def($request->getParsedBody()) ? strtoupper(collect($request->getParsedBody())->get('method')) : $request->getMethod() : GET;
				$this->url = $request->getUri()->getPath();
				$this->call_middleware($request);
			}
			
			/**
			 *
			 * Call the callable
			 *
			 * @throws Kedavra
			 * @throws DependencyException
			 * @throws NotFoundException
			 * @return RouteResult| RedirectResponse
			 */
			public function search()
			{
				
				foreach(Routes::where('method', EQUAL, $this->method)->all() as $route)
				{
					if($this->match($route->url))
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
			public function with($param, $regex) : Router
			{
				
				$this->regex[ $param ] = str_replace('(', '(?:', $regex);
				
				return $this;
			}
			
			/**
			 *
			 * Display a route url by this name
			 *
			 * @param  string  $name
			 *
			 * @param  array   $args
			 *
			 * @throws Kedavra
			 * @return string
			 *
			 */
			public static function url(string $name, array $args = []) : string
			{
				
				$x = route($name, $args);
				$host = request()->getHost();
				
				return php_sapi_name() !== 'cli' ? https() ? "https://$host/$x" : "http://$host/$x" : $x;
			}
			
			/**
			 *
			 * @param  ServerRequestInterface  $request
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 */
			private function call_middleware(ServerRequestInterface $request) : void
			{
				
				$middleware_dir = 'Middleware';
				
				$namespace = 'App' . '\\' . $middleware_dir . '\\';
				
				$dir = CORE . DIRECTORY_SEPARATOR . $middleware_dir;
				
				is_false(Dir::is($dir), true, "The $dir directory was not found");
				
				$middle = glob("$dir/*php");
				
				(new CsrfMiddleware())->handle($request);
				
				(new TrailingSlashMiddleware())->handle($request);
				
				(new AuthMiddleware())->handle($request);
				
				foreach($middle as $middleware)
				{
					$middle = collect(explode(DIRECTORY_SEPARATOR, $middleware))->last();
					
					$middleware = collect(explode('.', $middle))->first();
					
					$class = "$namespace$middleware";
					
					(new $class())->handle($request);
					
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
				
				array_shift($this->args);
				$params = collect();
				foreach($this->args as $match)
				{
					is_numeric($match) ? $this->with($match, NUMERIC) : $this->with($match, STRING);
					
					is_numeric($match) ? $params->set(intval($match)) : $params->set($match);
				}
				
				return new RouteResult(CONTROLLERS_NAMESPACE, $this->route->name, $this->route->url, $this->route->controller, $this->route->action, $params->all());
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
				
				return preg_match($regex, $this->url, $this->args) === 1;
			}
			
		}
	}
