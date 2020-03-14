<?php

declare(strict_types=1);

namespace Eywa\Ioc {


    use Eywa\Database\Connexion\Connect;
    use Eywa\Exception\Kedavra;
    use Eywa\Message\Flash\Flash;
    use ReflectionClass;
    use ReflectionException;

    class Ioc
    {


        /**
         *
         * All instances
         *
         */
        private static array $instances =[];

        /**
         *
         * All variables
         *
         */
        private static array $variables = [];


        /**
         *
         * Check if a key exist
         *
         * @param string $key
         *
         * @return bool
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        public static function has(string $key): bool
        {
            self::make();
            return array_key_exists($key,self::$variables) || array_key_exists($key,self::$instances);
        }

        /**
         *
         * @param string $key
         * @param array $args
         *
         * @return mixed
         *
         * @throws Kedavra
         * @throws ReflectionException
         */
        public static function get(string $key,array $args = [])
        {
            self::make();

            $instances = collect(self::$instances);
            $variables = collect(self::$variables);

            if ($instances->has($key))
                return $instances->get($key);
            elseif ($variables->has($key))
                return $variables->get($key);

            return self::parse($key,$args);
        }

        /**
         *
         * Add a new instance
         *
         * @param string $key
         * @param callable $callback
         *
         * @return Ioc
         *
         */
        public function init(string $key,callable $callback): Ioc
        {
            if (array_key_exists($key,self::$instances))
                return $this;

            self::$instances[$key] = call_user_func($callback);

            return $this;
        }

        /**
         *
         * Add a new variable
         *
         * @param string $key
         * @param mixed  $value
         *
         * @return Ioc
         *
         */
        public function set(string $key,$value): Ioc
        {
            self::$variables[$key] = $value;

            return $this;
        }

        /**
         *
         * Generate the container
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        private static function make(): void
        {
            if (count(self::$instances) == 0 || count(self::$variables) == 0)
            {
                foreach (files(base('ioc','*.php')) as $container)
                {
                    $namespace = '\Ioc\\';

                    $container = collect(explode('.',collect(explode(DIRECTORY_SEPARATOR,$container))->last()))->first();

                    $x = "$namespace$container";
                    $x = new ReflectionClass($x);

                    $extern_container = $x->getMethod('add')->invoke($x->newInstance());

                    $x = new ReflectionClass($extern_container);

                    self::$instances = array_merge(self::$instances,$x->getMethod('instances')->invoke($x->newInstance()));
                    self::$variables = array_merge(self::$variables,$x->getMethod('variables')->invoke($x->newInstance()));

                    self::$instances[Connect::class] = equal(config('mode','connexion'),'prod') ? production() : development();

                    self::$variables['faker'] = faker(strval(config('i18n','locale')));
                    self::$variables['flash'] = new Flash();
                }
            }
        }

        /**
         *
         * Get all instances
         *
         * @return array
         *
         */
        public function instances(): array
        {
            return self::$instances;
        }

        /**
         *
         * Get all variables
         *
         * @return array
         *
         */
        public function variables(): array
        {
            return self::$variables;
        }


        /**
         * @param string $key
         * @param array $args
         * @return object
         * @throws Kedavra
         * @throws ReflectionException
         */
        private static function parse(string $key,array $args =[]): object
        {

            $instances = [];
            $youldlike = new ReflectionClass($key);

            $youldlike_constructor = $youldlike->getConstructor();
            $youldlike_parameters =   !is_null($youldlike_constructor) ?  $youldlike_constructor->getParameters() : [] ;

            if (not_def($youldlike_parameters) && $youldlike->isInstantiable())
            {
                $instances[] =  $youldlike->newInstance();
                self::$instances[$key] =  $youldlike->newInstanceArgs($instances);
                return self::get($key);
            }

            for ($i=0;$i<count($youldlike_parameters);$i++)
            {
                $parameter = $youldlike_parameters[$i];
                $name =  $parameter->getClass();
                $class = is_null($name) ? $parameter->getName() : $name->getName();

                if (class_exists($class))
                {
                    if (is_false(self::has($class)))
                    {
                        $x =  new ReflectionClass($class);
                        $constructor = $x->getConstructor();
                        $parameters = is_null($constructor) ? [] : $constructor->getParameters();
                        $params = [];
                        foreach ($parameters as $parameter)
                        {
                            $current = $parameter->getClass();
                            $current = is_null($current) ? $parameter->getName() : $current->getName();

                            if (is_false(self::has($current)))
                            {
                                throw new Kedavra(sprintf('We have not found the %s parameter in the container',$current));
                            }else{
                                $params[] = self::get($current);
                            }
                        }
                        $instances[] = $x->newInstanceArgs($params);

                    }else{
                        $instances[] = self::get($class);
                    }
                }else
                {
                    if (array_key_exists($class,$args))
                    {
                        $instances[] = $args[$class];
                    }else{
                        is_false(self::has($class),true,sprintf('We have not found the %s parameter in the container',$class));
                        $instances[] = self::get($class);
                    }

                }
            }
            self::$instances[$key] =  $youldlike->newInstanceArgs($instances);
            return self::get($key);

        }
    }
}