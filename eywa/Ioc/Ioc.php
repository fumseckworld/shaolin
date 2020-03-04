<?php

declare(strict_types=1);

namespace Eywa\Ioc {


    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Exception\Kedavra;
    use Eywa\Message\Flash\Flash;

    class Ioc
    {

        /**
         *
         * All instances
         *
         */
        private Collect $instances;

        /**
         * @var Collect
         */
        private Collect $variables;
        /**
         * @var Collect
         */
        private $temp_variables;
        /**
         * @var Collect
         */
        private $temp_instances;


        /**
         * Ioc constructor.
         */
        public function __construct()
        {
            $this->instances = collect();
            $this->variables = collect();
            $this->temp_instances = collect();
            $this->temp_variables = collect();
        }

        /**
         *
         * Add instances in the container
         *
         * @param string $class
         * @param callable $callback
         *
         * @return Ioc
         *
         */
        public function init(string $class,callable $callback):Ioc
        {
            if ($this->instances->has($class))
                return $this;
            $this->temp_instances->put($class, call_user_func($callback));
            $this->instances = $this->instances->merge($this->temp_instances->all());
            $this->temp_instances->clear();


            return $this;

        }



        /**
         *
         * Add variable in the container
         *
         * @param string $key
         * @param mixed $value
         *
         * @return Ioc
         *
         */
        public function set(string $key,$value): Ioc
        {
            $this->temp_variables->put($key,$value);
            $this->variables = $this->variables->merge($this->temp_variables->all());
            $this->temp_variables->clear();

            return $this;
        }


        /**
         *
         * Check if a key exist in the container
         *
         * @param string $key
         * @return bool
         *
         * @throws Kedavra
         * @throws \ReflectionException
         *
         */
        public function has(string $key):bool
        {
            return $this->container()->instances->has($key) || $this->container()->variables->has($key);
        }
        /**
         *
         * Get an instance of a class
         *
         * @param string $class
         *
         * @return object
         *
         * @throws Kedavra
         *
         * @throws \ReflectionException
         *
         *
         */
        public function get(string $class): object
        {

            $instances = collect($this->container()->instances());
            $variables = collect($this->container()->variables());

            if ($instances->has($class))
                return $instances->get($class);
            elseif($variables->has($class))
                return $variables->get($class);



            $reflect = new \ReflectionClass($class);

            if (!is_null($reflect->getConstructor()))
            {
                if ($reflect->getConstructor()->getNumberOfRequiredParameters() === 0)
                {
                    $this->instances->put($class, $reflect->newInstance());

                }else
                {
                    $args = collect();

                   foreach ($reflect->getConstructor()->getParameters() as $parameter)
                   {
                       if (!is_null($parameter->getClass()))
                       {
                           $arg = $parameter->getClass()->getName();

                           is_false($this->container()->instances->has($arg),true,sprintf('Cannot create an instance for %s class because the %s value has not been found inside the container',$class,$arg));


                           $args->push($this->container()->instances->get($arg));

                       }

                       if (is_null($parameter->getClass()))
                       {
                           $arg = $parameter->getName();

                           is_false($this->container()->variables->has($arg),true,sprintf('Cannot create an instance for %s class because the %s value has not been found inside the container',$class,$arg));

                           $args->push($this->container()->variables->get($arg));

                       }
                   }
                   if (def($args->all()))
                       $this->instances->put($class,$reflect->newInstanceArgs($args->all()));

                }
            }else
            {

                $this->instances->put($class, $reflect->newInstance());
            }

            return $this->container()->get($class);
        }

        /**
         *
         * Get the container
         *
         * @return Ioc
         *
         * @throws Kedavra
         * @throws \ReflectionException
         *
         */
        private function container(): Ioc
        {
            if ($this->instances->empty())
            {
                foreach (glob(base('ioc','*.php')) as $container)
                {
                    $namespace = '\Ioc\\';

                    $container = \collect(explode('.',collect(explode(DIRECTORY_SEPARATOR,$container))->last()))->first();

                    $x = new \ReflectionClass("$namespace$container");

                   $extern_container = call_user_func($x->getMethod('build')->getClosure($x->newInstance()));

                   $this->instances->merge(call_user_func([$extern_container,'instances']));

                   $this->variables->merge(call_user_func([$extern_container,'variables']));

                }

                $this->init(Connect::class,function (){
                    return equal(config('mode','connexion'),'prod') ? production() : development();
                });
                $this->set('faker',faker(strval(config('i18n','locale'))));
                $this->set('flash',new Flash());

            }
            return $this;
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
            return $this->instances->all();
        }

        /**
         *
         * Get all instances
         *
         * @return array
         *
         */
        public function variables(): array
        {
            return $this->variables->all();
        }
    }
}