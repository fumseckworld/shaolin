<?php


namespace Eywa\Application {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
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