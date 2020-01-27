<?php


namespace Eywa\Application {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Eywa\Cache\Filecache;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Eywa\Html\Form\Form;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\Response;
    use Eywa\Ioc\Container;
    use Eywa\Message\Email\Write;
    use Eywa\Security\Authentication\Auth;
    use Eywa\Security\Crypt\Crypter;
    use Eywa\Session\Session;
    use Redis;

    interface Eywa
    {


        /**
         * @param string $key
         *
         * @return Container
         *
         * @throws Exception
         *
         */
        public function ioc(string $key): Container;

        /**
         *
         * Eywa constructor
         *
         */
        public function __construct();

        /**
         *
         * Get an environment value
         *
         * @param string $key
         *
         * @return mixed
         *
         */
        public function env(string $key);

        /**
         *
         * To write and send an email
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
        public function write(string $subject, string $message, string $author_email, string $to): Write;

        /**
         *
         * Get query builder
         *
         * @param string $table
         *
         * @return Sql
         *
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Exception
         *
         */
        public function sql(string $table): Sql;


        /**
         *
         * Check if the form is invalid
         *
         * @return bool
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public function check_form(): bool;

        /**
         *
         * Get an instance of session
         *
         * @return Session
         *
         */
        public function session(): Session;

        /**
         *
         *
         * Get and instance of autentication
         *
         * @return Auth
         *
         */
        public function auth(): Auth;

        /**
         *
         * Set a flash message
         *
         * @param string $key
         * @param string $message
         *
         * @return void
         *
         * @throws Kedavra
         *
         */
        public function flash(string $key,string $message): void;


        /**
         *
         * Get the form instance
         *
        * @param string $route
        * @param array $route_args
        * @param string $method
        * @param string $db
        *
         * @return Form
         *
         *
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Exception
         *
        */
        public function form(string $route,array $route_args = [],string $method = POST,string $db = 'web'): Form;

        /**
         *
         * Retrive a $_GET value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         */
        public function get(string $key,$default = null);

        /**
         *
         * Retrive a $_POST value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         */
        public function post(string $key,$default = null);

        /**
         *
         * Retrive a $_COOKIE value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         */
        public function cookie(string $key,$default = null);

        /**
         *
         * Retrive a $_SERVER value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         */
        public function server(string $key,$default = null);

        /**
         *
         * Get a $_FILE value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         */
        public function file(string $key,$default = null);

        /**
         *
         * Get a request instance
         *
         * @return Request
         *
         */
        public function request(): Request;

        /**
         *
         * Render a view
         *
         * @param string $view
         * @param string $title
         * @param string $description
         * @param array $args
         * @param string $layout
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function view(string $view,string $title,string $description,array $args =[],string $layout = 'layout.php'): Response;

        /**
         *
         * Run the application
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         *
         */
        public function run(): Response;

        /**
         *
         * Ge the encrypter instance
         *
         * @return Crypter
         *
         * @throws Kedavra
         *
         */
        public function crypter(): Crypter;

        /**
         *
         * Get and instance of redis
         *
         * @return Redis
         *
         */
        public function redis(): Redis;

        /**
         *
         * Go back
         *
         * @param string $message
         * @param bool $success
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function back(string $message ='',bool $success = true): Response;

        /**
         *
         * Go to a specific url by a route it's route name
         *
         * @param string $route
         * @param string $message
         * @param bool $success
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function to(string $route,string $message ='',bool $success = true): Response;

        /**
         *
         * Get an instance of file cache
         *
         * @return Filecache
         *
         */
        public function cache(): Filecache;

        /**
         *
         * Decrypt a string
         *
         * @param string $x
         * @param bool $unzerialize
         *
         * @return string
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function decrypt(string $x,bool $unzerialize = true): string;

        /**
         *
         * Crypt a string
         *
         * @param string $x
         * @param bool $unzerialize
         *
         * @return string
         *
         * @throws Kedavra
         * @throws Exception
         *
         *
         */
        public function crypt(string $x,bool $unzerialize = true): string;
  }
}