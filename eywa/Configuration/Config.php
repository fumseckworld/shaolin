<?php

declare(strict_types=1);

namespace Eywa\Configuration {


    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Symfony\Component\Yaml\Yaml;

    class Config extends Yaml
    {

        /**
         *
         * Class Config
         *
         * @author  Willy Micieli
         *
         * @package Imperium\Config
         *
         * @license GPL
         *
         * @version 10
         *
         */

        /**
         * @var string
         *
         */
        const EXT = '.yaml';

        /**
         *
         * The config.yaml filename
         *
         */
        private string  $filename;

        /**
         *
         * The config.yaml key
         *
         */
        private string $key;

        private Collect $values;

        /**
         *
         * Config constructor.
         *
         *
         * @param string $file
         * @param string  $key
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $file, string $key)
        {

            $file = $this->path() . DIRECTORY_SEPARATOR . collect(explode('.', $file))->first() . self::EXT;

            is_false(File::exist($file), true, "The $file file  was not found at " . $this->path());

            $this->values = collect(self::parseFile($file));

            is_false($this->values->has($key), true, "The $key key was not found in the  $file at " . $this->path());

            $this->filename = $file;

            $this->key = $key;
        }

        /**
         * Get config.yaml path
         *
         * @method path
         *
         * @return string
         *
         */
        public function path(): string
        {
            return base('config');
        }

        /**
         *
         * Get the config.yaml value
         *
         * @method value
         *
         * @return mixed
         *
         */
        public function value()
        {
            return $this->values->get($this->key);
        }

    }
}