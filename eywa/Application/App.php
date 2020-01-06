<?php


namespace Eywa\Application {



    use Eywa\Application\Environment\Env;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Query\Sql;
    use Eywa\Ioc\Container;
    use Eywa\Message\Email\Write;
    use Eywa\Security\Crypt\Crypter;
    use Symfony\Component\HttpFoundation\RedirectResponse;

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
         * @inheritDoc
         */
        public function to(string $url, string $message = '', bool $success = true): RedirectResponse
        {
            return to($url, $message, $success);
        }

        /**
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
    }
}