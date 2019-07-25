<?php

namespace Imperium\Trans {

    use Exception;
    use Imperium\Directory\Dir;
    use Imperium\Exception\Kedavra;
    use Imperium\File\File;
    use Symfony\Component\Yaml\Yaml;

    class  Trans extends Yaml
    {

        /**
         *
         * @var string
         *
         */
        const CONFIG_DIR = 'locales';

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
         * @throws Exception
         */
        public function get(string $file,$key)
        {
            $file = collect(explode('.',$file))->first();

            self::init();

            $x = self::$config . DIRECTORY_SEPARATOR . $file .self::EXT;

            self::check($x);

            return collect(self::parseFile($x))->get($key);

        }


        /**
         * @param string $file
         *
         * @throws Exception
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

            if (equal(request()->getScriptName(),'./vendor/bin/phpunit'))
                self::$config = dirname(request()->server->get('SCRIPT_FILENAME'),3) .DIRECTORY_SEPARATOR .self::CONFIG_DIR;
            else
                self::$config = dirname(server('DOCUMENT_ROOT')) .DIRECTORY_SEPARATOR .self::CONFIG_DIR;

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