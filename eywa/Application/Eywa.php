<?php


namespace Eywa\Application {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Response\Response;
    use Eywa\Ioc\Container;
    use Eywa\Message\Email\Write;
    use Eywa\Security\Crypt\Crypter;
    use Symfony\Component\HttpFoundation\RedirectResponse;

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
         */
        public function sql(string $table): Sql;

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
         * Ge the encrypter instance
         *
         * @return Crypter
         *
         * @throws Kedavra
         *
         */
        public function crypter(): Crypter;
  }
}