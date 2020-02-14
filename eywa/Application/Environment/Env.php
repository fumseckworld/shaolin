<?php

declare(strict_types=1);
namespace Eywa\Application\Environment {


    use Dotenv\Dotenv;
    use Dotenv\Repository\Adapter\EnvConstAdapter;
    use Dotenv\Repository\Adapter\PutenvAdapter;
    use Dotenv\Repository\RepositoryBuilder;

    class Env
    {
        /**
         * @var Dotenv
         */
        private Dotenv $env;

        public function __construct()
        {

            $repository = RepositoryBuilder::create()
                ->withReaders([
                    new EnvConstAdapter(),
                ])
                ->withWriters([
                    new EnvConstAdapter(),
                    new PutenvAdapter(),
                ])
                ->immutable()
                ->make();

            $this->env =   Dotenv::create($repository, base(),'.env');
            $this->env->load();
            $this->env->required(['DEBUG','DB_DRIVER','DB_HOST','DB_PORT','DB_NAME', 'DB_USERNAME', 'DB_PASSWORD','DEVELOP_DB_DRIVER','DEVELOP_DB_HOST','DEVELOP_DB_PORT','DEVELOP_DB_NAME', 'DEVELOP_DB_USERNAME', 'DEVELOP_DB_PASSWORD','APP_NAME','APP_KEY','CIPHER','TRANSLATOR_EMAIL']);
        }

        /**
         *
         * Get an environement variable
         *
         * @param $variable
         *
         * @return array|false|string
         *
         */
        public function get($variable)
        {
            return getenv($variable);
        }
    }
}