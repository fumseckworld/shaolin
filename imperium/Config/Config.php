<?php

namespace Imperium\Config {

    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
    use Imperium\File\File;
    use Symfony\Component\Yaml\Yaml;

    class Config extends Yaml
    {

        /**
         *
         * @var string
         *
         */
        const CONFIG_DIR = 'config';

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
         * @param $key
         *
         * @return mixed
         *
         * @throws Kedavra
         *
         */
        public function get(string $file,$key)
        {
            $file = collection(explode('.',$file))->begin();

            self::init();

            $x = self::$config . DIRECTORY_SEPARATOR . $file .self::EXT;

            self::check($x);

            $data =  collection(self::parseFile($x));


            if (!$data->has_key($key))
                throw new Kedavra("The $key key was not found in the file $file at {$this->path()}");
            else
                return $data->get($key);
        }


        /**
         * @param string $file
         *
         * @throws Kedavra
         *
         */
        private static function check(string $file)
        {
            is_false(File::exist($file),true,"$file was not found at : " .self::$config );

        }

        /**
         * @throws Kedavra
         */
        public static function init()
        {

            if (def(request()->server->get('PWD')) && Dir::is('vendor'))
            {
                self::$config = request()->server->get('PWD') . DIRECTORY_SEPARATOR .self::CONFIG_DIR;

                is_false(Dir::is(self::$config),true,'We have not fond the config dir');

                return new static();
            }

            if (equal(request()->getScriptName(),'./vendor/bin/phpunit'))
                self::$config = dirname(request()->server->get('SCRIPT_FILENAME'),3) .DIRECTORY_SEPARATOR .self::CONFIG_DIR;
            else
                self::$config = dirname(request()->server->get('DOCUMENT_ROOT')) .DIRECTORY_SEPARATOR .self::CONFIG_DIR;

            is_false(Dir::is(self::$config),true,'We have not fond the config dir');

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