<?php

declare(strict_types=1);

namespace Eywa\Application {



    use Eywa\Application\Environment\Env;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Query\Sql;
    use Eywa\Http\Request\Request;
    use Eywa\Http\Request\Server;
    use Eywa\Http\Response\Response;
    use Eywa\Http\Routing\Router;
    use Eywa\Http\View\View;
    use Eywa\Ioc\Container;
    use Eywa\Message\Email\Write;
    use Eywa\Security\Crypt\Crypter;

    class App extends Zen implements Eywa
    {

        private Env $env;

        /**
         * @inheritDoc
         */
        public function __construct()
        {
            if (server(DISPLAY_BUGS, false))
                whoops();

            $this->env = new Env();

        }


        /**
         * @inheritDoc
         */
        public function env(string $key)
        {
            return $this->env->get($key);
        }

        /**
         * @inheritDoc
         */
        public function write(string $subject, string $message, string $author_email, string $to): Write
        {
            return new Write($subject, $message, $author_email, $to);
        }


        /**
         *
         * @inheritDoc
         *
         */
        public function ioc(string $key): Container
        {
            return Container::ioc($key);
        }

        /**
         * @inheritDoc
         */
        public function sql(string $table): Sql
        {
            return new Sql($this->ioc(Connect::class)->get(), $table);
        }

        /**
         * @inheritDoc
         */
        public function crypter(): Crypter
        {
            return new Crypter();
        }

        /**
         * @inheritDoc
         */
        public function view(string $view, string $title, string $description, array $args = [], string $layout = 'layout.php'): Response
        {
            return  new Response(new View($view,$title,$description,$args,$layout));
        }

        /**
         * @inheritDoc
         */
        public function get(string $key, $default = null)
        {
            return $this->request()->query()->get($key,$default);
        }

        /**
         * @inheritDoc
         */
        public function post(string $key, $default = null)
        {
            return $this->request()->request()->get($key,$default);
        }

        /**
         * @inheritDoc
         */
        public function cookie(string $key, $default = null)
        {
            return $this->request()->cookie()->get($key,$default);
        }

        /**
         * @inheritDoc
         */
        public function server(string $key, $default = null)
        {
           return $this->request()->server()->get($key,$default);
        }

        /**
         * @inheritDoc
         */
        public function file(string $key, $default = null)
        {
            return $this->request()->file()->get($key,$default);
        }

        /**
         * @inheritDoc
         */
        public function request(): Request
        {
            return Request::generate();
        }

        /**
         * @inheritDoc
         */
        public function run(): Response
        {
            return (new Router(Server::generate()))->run();
        }
    }
}