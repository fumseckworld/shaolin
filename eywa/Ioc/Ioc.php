<?php

declare(strict_types=1);

namespace Eywa\Ioc {


    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Exception\Kedavra;
    use ReflectionClass;
    use ReflectionException;
    use ReflectionParameter;

    abstract class Ioc
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
         * @throws ReflectionException
         * @throws Kedavra
         *
         */
        public static function has(string $key): bool
        {
            self::make();
            return array_key_exists($key, self::$variables) || array_key_exists($key, self::$instances);
        }

        /**
         *
         * @return Collect
         *
         * @throws Kedavra
         * @throws ReflectionException
         *
         */
        public static function list():Collect
        {
            self::make();
            return collect()->put('variables', self::$variables)->put('instances', self::$instances);
        }

        /**
         *
         * @param string $key
         * @param array $args
         *
         * @return mixed
         *
         * @throws ReflectionException
         * @throws Kedavra
         */
        public static function get(string $key, array $args = [])
        {
            self::make();

            $instances = collect(self::$instances);
            $variables = collect(self::$variables);

            if ($instances->has($key)) {
                return $instances->get($key);
            } elseif ($variables->has($key)) {
                return $variables->get($key);
            }

            return self::parse($key, $args);
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
        public function init(string $key, callable $callback): Ioc
        {
            if (array_key_exists($key, self::$instances)) {
                return $this;
            }

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
        public function set(string $key, $value): Ioc
        {
            self::$variables[$key] = $value;

            return $this;
        }

        /**
         *
         * Generate the container
         *
         * @throws ReflectionException
         * @throws Kedavra
         *
         */
        protected static function make(): array
        {
            if (count(self::$instances) == 0 || count(self::$variables) == 0) {
                $containers = collect(files(base('ioc', '*.php')))->merge(files(base('ioc', '*', '*.php')))->all();
                foreach ($containers as $container) {
                    $filename = function (array $data) {
                        return $data['filename'];
                    };
                    $container = '\\'.collect(explode(DIRECTORY_SEPARATOR, strval(strstr($container, '/ioc'))))->shift()->for('ucfirst')->for('pathinfo')->for($filename)->join('\\');


                    $x = new ReflectionClass($container);

                    $extern_container = $x->getMethod('add')->invoke($x->newInstance());

                    $x = new ReflectionClass($extern_container);

                    self::$instances = array_merge(self::$instances, $x->getMethod('instances')->invoke($x->newInstance()));
                    self::$variables = array_merge(self::$variables, $x->getMethod('variables')->invoke($x->newInstance()));

                    self::$instances[Connect::class] = equal(config('mode', 'connexion'), 'prod') ? production() : development();

                    self::$variables['faker'] = faker(strval(config('i18n', 'locale')));
                }
            }
            return collect()->put('variables', self::$variables)->put('instances', self::$instances)->all();
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
         *
         * Pase an object to get a new instance
         *
         * @param string $key
         * @param array $args
         *
         * @return object
         *
         * @throws ReflectionException
         *
         */
        private static function parse(string $key, array $args =[]): object
        {
            $reflection = new ReflectionClass($key);
            $constructor = $reflection->getConstructor();
            $youldlike_parameters =   !is_null($constructor) ?  $constructor->getParameters() : [] ;

            /**
             *
             * Capture all class
             *
             * @param ReflectionParameter $parameter
             *
             * @return string
             *
             */
            $capture = function (ReflectionParameter $parameter) {
                $x = $parameter->getClass();
                return is_null($x)? $parameter->getName()  : $x->getName();
            };


            /**
             *
             * Get the instance for a key
             *
             * @param string $key
             *
             * @return object
             *
             */
            $parse = function (string $key) use ($capture,$args) {
                $reflection = new ReflectionClass($key);
                $constructor = $reflection->getConstructor();
                $youldlike_parameters =   !is_null($constructor) ?  $constructor->getParameters() : [] ;
                $all = collect();
                foreach ($youldlike_parameters as $parameter) {
                    $current = call_user_func_array($capture, [$parameter]);


                    if (class_exists($current)) {
                        if (self::has($current)) {
                            $all->push(self::get($current));
                        }
                    } else {
                        if (array_key_exists($current, $args)) {
                            $all->push($args[$current]);
                        } else {
                            throw new Kedavra(sprintf('The %s parameter has not been found in the container', $current));
                        }
                    }
                }
                return $reflection->newInstanceArgs($all->all());
            };

            /**
             *
             * Add a new instance
             *
             * @param string $key
             *
             * @return mixed
             *
             */
            $add = function (string $key) use ($parse) {
                if (self::has($key)) {
                    return self::get($key);
                }

                return call_user_func_array($parse, [$key]);
            };

            /**
             *
             * Save instance and return it
             *
             * @param array $classes
             *
             * @return object
             *
             */
            $instance = function (array $classes) use ($key) {
                $reflection = new ReflectionClass($key);

                self::$instances[$key] = $reflection->newInstanceArgs($classes);

                return self::get($key);
            };

            return call_user_func_array($instance, [collect($youldlike_parameters)->for($capture)->for($add)->all()]);
        }
    }
}
