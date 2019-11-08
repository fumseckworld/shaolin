<?php

namespace Imperium {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Dotenv\Dotenv;
    use GuzzleHttp\Psr7\ServerRequest;
    use Imperium\Asset\Asset;
    use Imperium\Cache\Cache;
    use Imperium\Collection\Collect;
    use Imperium\Config\Config;
    use Imperium\Connexion\Connect;
    use Imperium\Cookies\Cookies;
    use Imperium\Curl\Curl;
    use Imperium\Dump\Dump;
    use Imperium\Encrypt\Crypt;
    use Imperium\Exception\Kedavra;
    use Imperium\File\Download;
    use Imperium\Redis\Redis;
    use Imperium\Session\Session;
    use Imperium\Shopping\Shop;
    use Imperium\Validator\Validator;
    use Imperium\Versioning\Git;
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
            if (server(DISPLAY_BUGS) == 'true')
                whoops();
            $this->connect = $this->app(Connect::class);
            $this->view = $this->app(View::class);
            $this->table = $this->app(Table::class);
            Dotenv::create(base(), '.env')->load();

        }

        /**
         *
         * Get a config value
         *
         * @param string $file
         * @param mixed $key
         *
         * @return mixed
         *
         * @throws Kedavra
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
         * @param string $filename
         * @param string $mode
         *
         * @return File
         *
         * @throws Kedavra
         *
         */
        public function file(string $filename, string $mode = READ_FILE_MODE): File
        {
            return new File($filename, $mode);
        }

        /**
         *
         * Check if a table exist
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function exist(string $table): bool
        {
            return $this->table()->exist($table);
        }

        /**
         *
         * Management of array
         *
         * @param array $data
         *
         * @return Collect
         *
         */
        public function collect(array $data = []): Collect
        {
            return collect($data);
        }

        /**
         *
         * Display all tables
         *
         * @return array
         *
         * @throws Kedavra
         *
         */
        public function tables(): array
        {
            return $this->table()->show();
        }

        /**
         *
         * Remove a table
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function remove(string $table): bool
        {
            return $this->table()->drop($table);
        }

        /**
         *
         * Empty all records in a table
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function truncate(string $table): bool
        {
            return $this->table()->truncate($table);
        }

        /**
         * @param string $method
         * @param string $db
         * @param string $route
         * @param array $route_args
         * @return Form
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function form(string $method,string $db, string $route,...$route_args): Form
        {
            return new Form($method,$db,$route,$route_args);
        }

        /**
         *
         * Get an instance of table
         *
         * @return Table
         *
         */
        public function table(): Table
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
        public function connect(): Connect
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
        public function flash(): Flash
        {
            return new Flash($this->session());
        }

        /**
         *
         * Get an instance of session
         *
         * @return SessionInterface
         *
         */
        public function session(): SessionInterface
        {
            return def(strstr(request()->getScriptName(), 'phpunit')) ? new ArraySession() : new Session();
        }

        /**
         *
         * Get an instance of request
         *
         * @return Request
         *
         */
        public function request(): Request
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
        public function auth(): Oauth
        {
            return new Oauth($this->session());
        }

        /**
         *
         * @param ServerRequestInterface $serverRequest
         *
         *
         * @return Router
         *
         * @throws Kedavra
         *
         */
        public function router(ServerRequestInterface $serverRequest): Router
        {
            return new Router($serverRequest);
        }

        /**
         *
         * Run application
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws LoaderError
         * @throws NotFoundException
         * @throws RuntimeError
         * @throws SyntaxError
         * @throws DependencyException
         *
         */
        public function run(): Response
        {
            if (equal(config('mode', 'mode'), 'up'))
            {

                $x = $this->router(ServerRequest::fromGlobals())->search();

                return $x instanceof RedirectResponse ? $x->send() : $x->call()->send();
            }

            if (equal(config('mode', 'mode'), 'admin') && equal($this->request()->getClientIp(),'127.0.0.1'))
            {

                $x = $this->router(ServerRequest::fromGlobals())->search();

                return $x instanceof RedirectResponse ? $x->send() : $x->call()->send();
            }

            if (equal(config('mode', 'mode'), 'todo') && equal($this->request()->getClientIp(),'127.0.0.1'))
            {

                $x = $this->router(ServerRequest::fromGlobals())->search();

                return $x instanceof RedirectResponse ? $x->send() : $x->call()->send();
            }


            return $this->view('maintenance', [], 503, ['Retry-After' => 600])->send();
        }

        /**
         *
         * Get an instance of assets
         *
         * @param string $filename
         *
         * @return Asset
         *
         */
        public function assets(string $filename): Asset
        {
            return new Asset($filename);
        }

        /**
         *
         * Get the app locale
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function lang(): string
        {
            return $this->cookies()->get('locale', $this->config('locales', 'locale'));
        }

        /**
         *
         * Get an instance of write to send mail
         *
         * @param string $subject
         * @param string $message
         * @param string $author_email
         * @param string $to
         *
         * @return Write
         *
         * @throws Kedavra
         *
         */
        public function write(string $subject, string $message, string $author_email, string $to): Write
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
        public function view(string $name, array $args = [], int $status = 200, array $headers = []): Response
        {
            return $this->response($this->view->load($name, $args), $status, $headers);
        }

        /**
         *
         * Redirect user to a route
         *
         * @param string $db
         * @param string $route
         * @param array $args
         * @param string $message
         * @param bool $success
         *
         * @return RedirectResponse
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function redirect(string $db,string $route,array $args =[],string $message = '', bool $success = true) : RedirectResponse
        {
            return redirect(route($db,$route,$args), $message, $success);
        }

        /**
         *
         * Redirect user back
         *
         * @param string $message
         * @param bool $success
         *
         * @return RedirectResponse
         * @throws NotFoundException
         * @throws DependencyException
         */
        public function back(string $message = '', bool $success = true): RedirectResponse
        {
            return back($message, $success);
        }

        /**
         *
         * Redirect user to an url
         *
         * @param string $url
         * @param string $message
         * @param bool $success
         *
         * @return RedirectResponse
         * @throws NotFoundException
         * @throws DependencyException
         */
        public function to(string $url, string $message = '', bool $success = true): RedirectResponse
        {
            return to($url, $message, $success);
        }

        /**
         *
         * @param string $content
         * @param int $status
         * @param array $headers
         *
         * @return Response
         *
         */
        public function response(string $content, int $status = 200, array $headers = []): Response
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
        public function cache(): Cache
        {

            return new Cache();
        }

        /**
         *
         * Download a file
         *
         * @param string $filename
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function download(string $filename): Response
        {

            return (new Download($filename))->download();
        }

        /**
         *
         * Generate url string
         *
         * @param string $db
         * @param string $route
         * @param mixed $args
         *
         * @return string
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function url(string $db,string $route,...$args) : string
        {
            return route($db,$route,$args);
        }

        /**
         *
         * Get query builder
         *
         * @param string $table
         * @param bool $web
         * @param bool $admin
         * @return Query
         *
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function sql(string $table, bool $web = false, $admin = false): Query
        {
            return Query::from($table, $web, $admin);
        }

        /**
         *
         * Init curl
         *
         * @return Curl
         */
        public function curl(): Curl
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
        public function shop(): Shop
        {
            return new Shop();
        }

        /**
         *
         * Save the database
         *
         * @return bool
         * @throws Kedavra
         * @throws NotFoundException
         * @throws DependencyException
         */
        public function save(): bool
        {
            return (new Dump(true, []))->dump();
        }

        /**
         *
         * Get an instance of validator
         *
         * @param array $data
         *
         * @return Validator
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function validator(array $data): Validator
        {
            return new Validator($this->collect($data));
        }

        /**
         *
         * Get an instance of cookies
         *
         * @return Cookies
         *
         */
        public function cookies(): Cookies
        {
            return new Cookies();
        }

        public function env($variable)
        {
            return getenv($variable);
        }

        /**
         *
         * Crypt data
         *
         * @param string $data
         *
         * @param bool $serialize
         * @return string
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function crypt(string $data, bool $serialize = true): string
        {
            return (new Crypt())->encrypt($data, $serialize);
        }

        /**
         *
         * Decrypt the encrypted value
         *
         * @param string $encrypted
         *
         * @param bool $unserialize
         * @return string
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function decrypt(string $encrypted, bool $unserialize = true): string
        {
            return (new Crypt())->decrypt($encrypted, $unserialize);
        }
	
		/**
		 *
		 * Get an instance of redis
		 *
		 * @return Redis
         *
		 */
        public function redis(): Redis
        {
            return new Redis();
        }

        /**
         *
         * Retrieve a $_GET value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         */
        public function get(string $key, $default = null)
        {
            return $this->request()->query->get($key,$default);
        }

        /**
         *
         * Retrieve a $_POST value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         */
        public function post(string $key, $default = null)
        {
            return $this->request()->request->get($key,$default);
        }

        /**
         *
         * Retrieve a $_COOKIE value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         */
        public function cookie(string $key, $default = null)
        {
            return $this->request()->cookies->get($key,$default);
        }

        /**
         *
         * Retrieve a $_FILES value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         */
        public function files(string $key, $default = null)
        {
            return $this->request()->files->get($key,$default);
        }

        /**
         *
         * Retrieve a $_SERVER value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         */
        public function server(string $key, $default = null)
        {
            return $this->request()->server->get($key,$default);
        }

        /**
         *
         * Management of git
         *
         * @param string $repository
         * @param string $branch
         * @param string $directory
         *
         * @return Git
         *
         * @throws Kedavra
         */
        public function git(string $repository, string $branch,string $directory =''): Git
        {
            return new Git($repository,$branch,$directory);
        }
    }
}
