<?php
	
	namespace Imperium\Routing
	{
		
		use DI\DependencyException;
		use DI\NotFoundException;
        use Imperium\Directory\Dir;
		use Imperium\Exception\Kedavra;
        use Imperium\Model\Admin;
        use Imperium\Model\Task;
        use Imperium\Model\Web;
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
			 * @throws Kedavra
			 *
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
				
				is_false(Dir::is($dir), true, "The $dir directory was not found");
				
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
				
				array_shift($this->args);
				
				$params = collect();
				
				foreach($this->args as $match)
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
				
				return preg_match($regex, $this->url, $this->args) === 1;
			}
			
		}
	}
