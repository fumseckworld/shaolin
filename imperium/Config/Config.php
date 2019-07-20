<?php

namespace Imperium\Config {

    use Imperium\Collection\Collection;
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
         *
         * The config file
         *
         * @var string
         *
         */
        private $file;

        /**
         *
         * The config key
         *
         * @var mixed
         *
         */
        private $key;
        /**
         * @var Collection
         */
        private $values;

        /**
         *
         * Config constructor.
         *
         *
         * @param string $file
         * @param $key
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $file,$key)
        {
            $file =  CONFIG . DIRECTORY_SEPARATOR . collection(explode('.',$file))->begin() .self::EXT;

            is_false(File::exist($file),true,"The $file file  was not found at ". $this->path());

            $this->values = collection(self::parseFile($file));

            is_false($this->values->has_key($key),true,"The $key key was not found in the  $file at ". $this->path());

            $this->file = $file;

            $this->key = $key;

        }

        /**
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
            return (new static($file,$key))->value();
        }

        /**
         * @return string
         */
        public function path(): string
        {
            return CONFIG;
        }

        public function value()
        {
            return $this->values->get($this->key);
        }
    }
}