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
    use Eywa\Exception\Kedavra;
    use Eywa\Message\Flash\Flash;

    class Container
    {

        private static ?\DI\Container  $ioc = null;

        /**
         * @return Container
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         * @throws Exception
         */
        public static function ioc(): Container
        {


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
                $c->set('db.dump',base('db','dump'));
                $c->set("views.path",base('app','Views')) ;
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
         * @param string $key
         * @return mixed
         *
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function get(string $key)
        {
            return self::container()->get($key);
        }


        /**
         *
         * Check if key is define
         *
         * @param string $key
         * @return bool
         */
        public function has(string $key): bool
        {
            return self::container()->has($key);
        }

        /**
         *
         * Set a new value
         *
         * @param string $key
         * @param $value
         *
         * @return Container
         */
        public function set(string $key,$value): Container
        {
            self::container()->set($key,$value);

            return $this;
        }

        /**
         *
         * Call the callback
         *
         * @param string $key
         * @param string $method
         * @param array $args
         *
         * @return mixed
         */
        public function call(string $key,string $method,array $args =[])
        {
            return self::container()->call([$key,$method],$args);
        }

        /**
         *
         * Debug an entry
         *
         * @param string $key
         * @return string
         *
         * @throws InvalidDefinition
         * @throws NotFoundException
         */
        public function debug(string $key)
        {
            return self::container()->debugEntry($key);
        }

        /**
         *
         * Build an entry of the container by its name
         *
         * @param string $key
         * @param array $args
         *
         * @return mixed
         *
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function make(string $key,array $args = [])
        {
            return self::container()->make($key,$args);
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