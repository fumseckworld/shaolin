<?php

declare(strict_types=1);

namespace Eywa\Ioc {


    use DI\ContainerBuilder;
    use DI\Definition\Exception\InvalidDefinition;
    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Eywa\Application\Environment\Env;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Table\Table;
    use Eywa\Message\Flash\Flash;

    class Container
    {

        private static string $key;

        private static ?\DI\Container  $ioc = null;

        /**
         * @param string $key
         * @return Container
         * @throws Exception
         */
        public static function ioc(string $key): Container
        {
            self::$key = $key;

            if (is_null(static::$ioc))
            {
                $env = new Env();

                $c = new ContainerBuilder();
                $c->useAnnotations(true);
                $c->useAutowiring(true);
                $c = $c->build();
                $c->set('db.driver',$env->get('DB_DRIVER'));
                $c->set('db.name', $env->get('DB_NAME'));
                $c->set('db.username', $env->get('DB_USERNAME'));
                $c->set('db.password',$env->get('DB_PASSWORD'));
                $c->set('db.host',$env->get('DB_HOST'));
                $c->set('db.port',intval($env->get('DB_PORT')));
                $c->set('db.options',[]);
                $c->set('db.dump',base('db') .DIRECTORY_SEPARATOR .'dump' );
                $c->set("views.path",base('app'). DIRECTORY_SEPARATOR . 'views') ;
                $c->set("flash",new Flash()) ;
                $c->set('faker',faker(config('i18n','locale'))) ;
                $c->set('table',new Table($c->get(Connect::class))) ;
                self::$ioc = $c;

            }
            return new static();
        }



        /**
         *
         * Get the value
         *
         * @return mixed
         *
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function get()
        {
            return self::container()->get(self::$key);
        }


        /**
         *
         * Check if key is define
         *
         * @return bool
         */
        public function has(): bool
        {
            return self::container()->has(self::$key);
        }

        /**
         *
         * Set a new value
         *
         * @param $value
         *
         * @return Container
         *
         */
        public function set($value): Container
        {
            self::container()->set(self::$key,$value);

            return $this;
        }

        /**
         *
         * Call the callback
         *
         * @param string $method
         * @param array $args
         *
         * @return mixed
         */
        public function call(string $method,array $args =[])
        {
            return self::container()->call([self::$key,$method],$args);
        }

        /**
         *
         * Debug an entry
         *
         * @return string
         *
         * @throws NotFoundException
         * @throws InvalidDefinition
         */
        public function debug()
        {
            return self::container()->debugEntry(self::$key);
        }

        /**
         *
         * Build an entry of the container by its name
         *
         * @param array $args
         *
         * @return mixed
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         */
        public function make(array $args = [])
        {
            return self::container()->make(self::$key,$args);
        }

        /**
         * @return \DI\Container|null
         */
        private static function container()
        {
            return self::$ioc;
        }
    }
}