<?php
	
	namespace Imperium
	{
		
		use DI\DependencyException;
		use DI\NotFoundException;
		use Dotenv\Dotenv;
		use GuzzleHttp\Psr7\ServerRequest;
		use Imperium\Asset\Asset;
		use Imperium\Cache\Cache;
		use Imperium\Collection\Collect;
		use Imperium\Config\Config;
		use Imperium\Connexion\Connect;
		use Imperium\Curl\Curl;
		use Imperium\Dump\Dump;
		use Imperium\Exception\Kedavra;
		use Imperium\File\Download;
		use Imperium\Session\Session;
		use Imperium\Shopping\Shop;
		use Imperium\View\View;
		use Imperium\Writing\Write;
		use Imperium\File\File;
		use Imperium\Flash\Flash;
		use Imperium\Html\Form\Form;
		use Imperium\Query\Query;
		use Imperium\Routing\Router;
		use Imperium\Security\Auth\Oauth;
		use Imperium\Session\ArraySession;
		use Imperium\Session\SessionInterface;
		use Imperium\Tables\Table;
		use Psr\Http\Message\ServerRequestInterface;
		use Symfony\Component\HttpFoundation\RedirectResponse;
		use Symfony\Component\HttpFoundation\Request;
		use Symfony\Component\HttpFoundation\Response;
		use Twig\Error\LoaderError;
		use Twig\Error\RuntimeError;
		use Twig\Error\SyntaxError;
		
		/**
		 * Class App
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class App extends Zen implements Management
		{
			
			/**
			 * @var Table
			 */
			private $table;
			
			/**
			 * @var View
			 */
			private $view;
			
			/**
			 * @var Connect
			 */
			private $connect;
			
			/**
			 * App constructor.
			 *
			 * @throws DependencyException
			 * @throws NotFoundException
			 *
			 */
			public function __construct()
			{
				
				$this->connect = $this->app(Connect::class);
				$this->view = $this->app(View::class);
				$this->table = $this->app(Table::class);
				Dotenv::create(base(), '.env')->load();
			}
			
			/**
			 *
			 * Get a config value
			 *
			 * @param  string  $file
			 * @param  mixed   $key
			 *
			 * @throws Kedavra
			 *
			 * @return mixed
			 *
			 */
			public function config(string $file, $key)
			{
				
				return (new Config($file, $key))->value();
			}
			
			/**
			 *
			 * File management
			 *
			 * @param  string  $filename
			 * @param  string  $mode
			 *
			 * @throws Kedavra
			 *
			 * @return File
			 *
			 */
			public function file(string $filename, string $mode = READ_FILE_MODE) : File
			{
				
				return new File($filename, $mode);
			}
			
			/**
			 *
			 * Check if a table exist
			 *
			 * @param  string  $table
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function exist(string $table) : bool
			{
				
				return $this->table()->exist($table);
			}
			
			/**
			 *
			 * Management of array
			 *
			 * @param  array  $data
			 *
			 * @return Collect
			 *
			 */
			public function collect(array $data = []) : Collect
			{
				
				return collect($data);
			}
			
			/**
			 *
			 * Display all tables
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function tables() : array
			{
				
				return $this->table()->show();
			}
			
			/**
			 *
			 * Remove a table
			 *
			 * @param  string  $table
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function remove(string $table) : bool
			{
				
				return $this->table()->drop($table);
			}
			
			/**
			 *
			 * Empty all records in a table
			 *
			 * @param  string  $table
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function truncate(string $table) : bool
			{
				
				return $this->table()->truncate($table);
			}
			
			/**
			 * @return Form
			 */
			public function form() : Form
			{
				
				return new Form();
			}
			
			/**
			 *
			 * Get an instance of table
			 *
			 * @return Table
			 *
			 */
			public function table() : Table
			{
				
				return $this->table;
			}
			
			/**
			 *
			 * Get an instance of database
			 *
			 * @return Connect
			 *
			 */
			public function connect() : Connect
			{
				
				return $this->connect;
			}
			
			/**
			 *
			 * Get an instance of flash message
			 *
			 * @return Flash
			 *
			 */
			public function flash() : Flash
			{
				
				return new Flash($this->session());
			}
			
			/**
			 * Get an instance of session
			 *
			 * @return SessionInterface
			 *
			 */
			public function session() : SessionInterface
			{
				
				return def(strstr(request()->getScriptName(), 'phpunit')) ? new ArraySession() : new Session();
			}
			
			/**
			 *
			 * Get an instance of request
			 *
			 * @return Request
			 */
			public function request() : Request
			{
				
				return Request::createFromGlobals();
			}
			
			/**
			 *
			 * Get an instance of auth class
			 *
			 * @return Oauth
			 *
			 */
			public function auth() : Oauth
			{
				
				return new Oauth($this->session());
			}
			
			/**
			 *
			 * @param  ServerRequestInterface  $serverRequest
			 *
			 *
			 * @throws Kedavra
			 * @return Router
			 *
			 */
			public function router(ServerRequestInterface $serverRequest) : Router
			{
				
				return new Router($serverRequest);
			}

            /**
             *
             * Run application
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws LoaderError
             * @throws NotFoundException
             * @throws RuntimeError
             * @throws SyntaxError
             *
             * @return Response
             *
             */
			public function run() : Response
			{
				if(equal(config('mode','mode'),'up'))
				{
					$x = $this->router(ServerRequest::fromGlobals())->search();
				
					return $x instanceof RedirectResponse ? $x->send() : $x->call()->send();
				}

				return $this->view('maintenance',[],503,['Retry-After' => 600])->send();
			}
			
			/**
			 *
			 * Get an instance of assets
			 *
			 * @param  string  $filename
			 *
			 * @return Asset
			 *
			 */
			public function assets(string $filename) : Asset
			{
				
				return new Asset($filename);
			}
			
			/**
			 *
			 * Get the app locale
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function lang() : string
			{
				
				return $this->config('locales', 'locale');
			}
			
			/**
			 *
			 * Get an instance of write to send mail
			 *
			 * @param  string  $subject
			 * @param  string  $message
			 * @param  string  $author_email
			 * @param  string  $to
			 *
			 * @throws Kedavra
			 *
			 * @return Write
			 *
			 */
			public function write(string $subject, string $message, string $author_email, string $to) : Write
			{
				
				return new Write($subject, $message, $author_email, $to);
			}

            /**
             *
             * Return a view
             *
             * @param string $name
             * @param array $args
             * @param int $status
             * @param array $headers
             * @return Response
             * @throws LoaderError
             * @throws RuntimeError
             * @throws SyntaxError
             */
			public function view(string $name,array $args = [],int $status= 200,array $headers = []) : Response
			{
				
				return $this->response($this->view->load($name, $args),$status,$headers);
			}
			
			/**
			 *
			 * Redirect user to a route
			 *
			 * @param  string  $route
			 * @param  string  $message
			 * @param  bool    $success
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return RedirectResponse
			 *
			 */
			public function redirect(string $route, string $message = '', bool $success = true) : RedirectResponse
			{
				
				return redirect($route, $message, $success);
			}
			
			/**
			 *
			 * Redirect user back
			 *
			 * @param  string  $message
			 * @param  bool    $success
			 *
			 * @throws DependencyException
			 * @throws NotFoundException
			 * @return RedirectResponse
			 */
			public function back(string $message = '', bool $success = true) : RedirectResponse
			{
				
				return back($message, $success);
			}
			
			/**
			 *
			 * Redirect user to an url
			 *
			 * @param  string  $url
			 * @param  string  $message
			 * @param  bool    $success
			 *
			 * @throws DependencyException
			 * @throws NotFoundException
			 * @return RedirectResponse
			 */
			public function to(string $url, string $message = '', bool $success = true) : RedirectResponse
			{
				
				return to($url, $message, $success);
			}
			
			/**
			 *
			 * @param  string  $content
			 * @param  int     $status
			 * @param  array   $headers
			 *
			 * @return Response
			 *
			 */
			public function response(string $content, int $status = 200, array $headers = []) : Response
			{
				
				return new Response($content, $status, $headers);
			}
			
			/**
			 *
			 * Get cache instance
			 *
			 * @return Cache
			 *
			 */
			public function cache() : Cache
			{
				
				return new Cache();
			}
			
			/**
			 *
			 * Download a file
			 *
			 * @param  string  $filename
			 *
			 * @throws Kedavra
			 *
			 * @return Response
			 *
			 */
			public function download(string $filename) : Response
			{
				
				return (new Download($filename))->download();
			}
			
			/**
			 *
			 * Generate url string
			 *
			 * @param  string  $route
			 * @param  mixed   $args
			 *
			 * @throws Kedavra
			 * @return string
			 *
			 */
			public function url(string $route, ...$args) : string
			{
				
				return route($route, $args);
			}
			
			/**
			 *
			 * Get query builder
			 *
			 * @param  string  $table
			 * @param  bool    $routes
			 *
			 * @throws DependencyException
			 * @throws NotFoundException
			 *
			 * @return Query
			 *
			 */
			public function sql(string $table, bool $routes = false) : Query
			{
				
				return Query::from($table, $routes);
			}
			
			/**
			 *
			 * Init curl
			 *
			 * @return Curl
			 */
			public function curl() : Curl
			{
				
				return new Curl();
			}
			
			/**
			 *
			 * Get and instance of shop class
			 *
			 * @return Shop
			 *
			 */
			public function shop() : Shop
			{
				
				return new Shop();
			}
			
			/**
			 *
			 * Save the database
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
			 */
			public function save() : bool
			{
				
				return (new Dump(true, []))->dump();
			}
		}
	}
