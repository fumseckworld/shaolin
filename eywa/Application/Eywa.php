<?php

declare(strict_types=1);

namespace Eywa\Application {


    use Exception;
    use Eywa\Cache\CacheInterface;
    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Query\Sql;
    use Eywa\Detection\Detect;
    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Eywa\Http\Parameter\Bag;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\Response;
    use Eywa\Message\Email\Write;
    use Eywa\Security\Authentication\Auth;
    use Eywa\Security\Crypt\Crypter;
    use Eywa\Session\SessionInterface;
    use Redis;
    use ReflectionException;

    interface Eywa
    {


        /**
         * @param string $key
         *
         * @return mixed
         *
         * @throws Kedavra
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
        public function env(string $key, $default = '');

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
        public function config(string $file, string $key);

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
         *
         * Get a model
         *
         * @param string $model
         * @param string $method
         * @param array $method_args
         *
         * @return mixed
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         *
         */
        public function model(string $model, string $method, array $method_args = []);

        /**
         *
         * Generate a response
         *
         * @param string $content
         * @param int $status
         * @param array<string> $headers
         * @param string $url
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function response(string $content, int $status = 200, array $headers = [], string $url = ''): Response;

        /**
         *
         * Redirect user to an another route
         *
         * @param string $url
         * @param string $message
         * @param bool $success
         * @param int $status
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function redirect(string $url, string $message, bool $success, int $status = 301): Response;

        /**
         *
         * Generate the form
         *
         * @param string $form
         *
         * @return string
         *
         * @throws ReflectionException
         *
         */
        public function form(string $form): string;

        /**
         *
         * Validate a form
         *
         * @param string $form
         * @param Request $request
         *
         * @return Response
         * @throws ReflectionException
         *
         */
        public function check(string $form, Request $request): Response;

        /**
         *
         * Check a request
         *
         * @param string $validator
         * @param Bag $bag
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws Exception
         * @throws ReflectionException
         *
         */
        public function validate(string $validator, Bag $bag): Response;

        /**
         *
         * Get an instance of collect
         *
         * @param array<mixed> $data
         *
         * @return Collect
         *
         */
        public function collect(array $data = []): Collect;


        /**
         *
         * Get the query builder
         *
         * @param string $table
         *
         * @return Sql
         *
         * @throws Kedavra
         *
         */
        public function sql(string $table): Sql;


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
         * @param string $model
         *
         * @return Auth
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function auth(string $model): Auth;

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
        public function flash(string $key, string $message): void;



        /**
         *
         * Retrive a $_GET value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         * @throws Kedavra
         *
         *
         */
        public function get(string $key, $default = null);

        /**
         *
         * Retrive a $_POST value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         * @throws Kedavra
         *
         */
        public function post(string $key, $default = null);

        /**
         *
         * Retrive a $_COOKIE value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         * @throws Kedavra
         *
         */
        public function cookie(string $key, $default = null);


        /**
         *
         * Retrive a $_SERVER value
         *
         * @param string $key
         * @param null $default
         *
         * @return mixed
         *
         * @throws Kedavra
         *
         */
        public function server(string $key, $default = null);

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
        public function file(string $filename, string $mode = READ_FILE_MODE): File;

        /**
         *
         * Get a request instance
         *
         * @param array<mixed> $args
         *
         * @return Request
         *
         * @throws Kedavra
         *
         *
         */
        public function request(array $args = []): Request;

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
         * @param array<mixed> $args
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws Exception
         *
         *
         */
        public function view(string $view, string $title, string $description, array $args = []): Response;

        /**
         *
         * Run the application
         *
         * @return Response
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function run(): Response;

        /**
         *
         * Return a json response
         *
         * @param array<mixed> $data
         * @param int $status
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function json(array $data, int $status = 200): Response;

        /**
         *
         * Get an instance of connexion (PDO)
         *
         * @return Connect
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function connexion(): Connect;

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
        public function back(string $message = '', bool $success = true): Response;

        /**
         *
         * Go to a specific url by a route it's route name
         *
         * @param string $route
         * @param array<mixed> $route_args
         * @param string $message
         * @param bool $success
         *
         * @return Response
         * @throws Kedavra
         */
        public function to(string $route, array $route_args = [], string $message = '', bool $success = true): Response;

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
        public function decrypt(string $x, bool $unzerialize = true): string;

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
        public function crypt(string $x, bool $unzerialize = true): string;
    }
}
