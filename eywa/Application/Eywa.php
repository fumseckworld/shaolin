<?php

declare(strict_types=1);

namespace Eywa\Application {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Eywa\Cache\CacheInterface;
    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connexion;
    use Eywa\Database\Query\Sql;
    use Eywa\Detection\Detect;
    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Eywa\Html\Form\Form;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\Response;
    use Eywa\Message\Email\Write;
    use Eywa\Security\Authentication\Auth;
    use Eywa\Security\Crypt\Crypter;
    use Eywa\Security\Validator\Validator;
    use Eywa\Session\Session;
    use Eywa\Session\SessionInterface;
    use Redis;

    interface Eywa
    {


        /**
         * @param string $key
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function ioc(string $key);

        /**
         *
         * Eywa constructor
         * @throws Kedavra
         */
        public function __construct();

        /**
         *
         * Get an environment value
         *
         * @param string $key
         * @param string $default
         *
         * @return mixed
         *
         */
        public function env(string $key,$default = '');

        /**
         *
         * Get a config value
         *
         * @param string $file
         * @param string $key
         *
         * @return mixed
         *
         * @throws Kedavra
         *
         */
        public function config(string $file,string $key);

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
         * Generate a response
         *
         * @param string $content
         * @param int $status
         * @param array $headers
         * @param string $url
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function response(string $content, int $status = 200, array $headers = [], string $url =''): Response;

        /**
         *
         * Redirect user to an another route
         *
         * @param string $route
         * @param array $route_args
         * @param string $message
         * @param bool $success
         * @param int $status
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function redirect(string $route,array $route_args,string $message,bool $success,int $status = 301): Response;


        /**
         *
         * Get an instance of collect
         *
         * @param array $data
         *
         * @return Collect
         *
         */
        public function collect(array $data = []): Collect;

        /**
         *
         * Get the validator
         *
         * @param array $data
         * @param string $lang
         *
         * @return Validator
         *
         * @throws Kedavra
         *
         *
         */
        public function validator(array $data,string $lang = 'en'): Validator;

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
         * @return SessionInterface
         *
         */
        public function session(): SessionInterface;

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
         * Get the form builder instance
         *
         * @param string $route
         * @param string $method
         * @param array $params
         * @param array $route_args
         *
         * @return Form
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function form(string $route,string $method,array $route_args = [],array $params = []) : Form;

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
         * Get a $_FILES value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         */
        public function files(string $key,$default = null);

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
         * @param string $filename
         * @param string $mode
         *
         * @return File
         *
         * @throws Kedavra
         *
         */
        public function file(string $filename,string $mode = READ_FILE_MODE): File;

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
         * Get an instance of detection
         *
         * @return Detect
         *
         */
        public function detect(): Detect;

        /**
         *
         * Render a view
         *
         * @param string $view
         * @param string $title
         * @param string $description
         * @param array $args
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         *
         */
        public function view(string $view,string $title,string $description,array $args =[]): Response;

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
         * Return a json response
         *
         * @param array $data
         * @param int $status
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function json(array $data,int $status = 200): Response;

        /**
         *
         * Get an instance of connexion (PDO)
         *
         * @return Connexion
         *
         * @throws Kedavra
         *
         */
        public function connexion(): Connexion;

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
         * @throws DependencyException
         * @throws NotFoundException
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
         * @throws Exception
         *
         */
        public function to(string $route,string $message ='',bool $success = true): Response;

        /**
         *
         * Get an instance of file cache
         *
         * @param int $type
         *
         * @return CacheInterface
         *
         * @throws Kedavra
         *
         */
        public function cache(int $type = FILE_CACHE): CacheInterface;

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