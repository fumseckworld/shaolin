<?php


namespace Imperium\Config {

    use Exception;
    use Imperium\Directory\Dir;
    use Imperium\File\File;
    use Symfony\Component\Yaml\Yaml;

    class Routes extends Yaml
    {
        /**
         *
         * @var string
         *
         */
        const CONFIG_DIR = 'Routes';

        /**
         * @var string
         *
         */
        const EXT = '.yaml';

        /**
         * @var string
         */
        const DELIMITER = ':';


        /**
         * @var string
         */
        private static $config;

        /**
         *
         * Get a config value
         *
         * @param string $file
         * @param string method
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function get(string $file, string $method)
        {
            $file = collection(explode('.', $file))->begin();

            self::init();

            $x = self::$config . DIRECTORY_SEPARATOR . $file . self::EXT;

            self::check($x);

            if (equal($file, 'db'))
                return collection(collection(self::parseFile($x))->get(collection(self::parseFile($x))->get('use')))->get($method);


            $data = collection(self::parseFile($x));


            if (!$data->has_key($method))
                throw new Exception("The $method key was not found in the file $file at {$this->path()}");

            $result = collection();

            $names = collection();

            foreach ($data->get($method) as $prefix => $routes)
            {
                foreach ($routes as $name => $route)
                {

                    is_true($names->exist($name),true,"The route name $name is not unique");

                    $names->add($name);

                    $result->push(collection($route)->add($name,'name')->add($prefix,'prefix')->collection());

                }

            }
            return $result->collection();

        }


        /**
         * 
         * @param string $file
         *
         * @throws Exception
         *
         */
        private static function check(string $file)
        {
            is_false(File::exist($file), true, "$file was not found at : " . self::$config);

        }

        /**
         * @throws Exception
         */
        public static function init()
        {

            $core = core_path(collection(config('app','dir'))->get('app'));

            if (equal(request()->getScriptName(), './vendor/bin/phpunit'))
                self::$config = $core .DIRECTORY_SEPARATOR .self::CONFIG_DIR;
            else
                self::$config =  $core . DIRECTORY_SEPARATOR .self::CONFIG_DIR;


            if(def(request()->server->get('PWD')))
            {

                self::$config =  $core . DIRECTORY_SEPARATOR .self::CONFIG_DIR;
            }

          
            is_false(Dir::is(self::$config), true, 'We have not fond the config dir');

            return new static();

        }

        /**
         * @return string
         */
        public function path(): string
        {
            return self::$config;
        }
    }
}
