<?php

declare(strict_types=1);

namespace Eywa\Cache {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Exception\Kedavra;
    use Eywa\File\File;

    class FileCache implements CacheInterface
    {

        /**
         * @inheritDoc
         */
        public function get(string $key)
        {
            if ($this->has($key))
            {
                return (new File($this->file($key)))->read();

            }
            return false;
        }

        /**
         * @inheritDoc
         */
        public function set(string $key, $value): CacheInterface
        {
            (new File($this->file($key),EMPTY_AND_WRITE_FILE_MODE))->write($value)->flush();

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function destroy(string $key): bool
        {
            return  (new File($this->file($key)))->remove();
        }

        /**
         * @inheritDoc
         */
        public function has(string $key): bool
        {
            return file_exists($this->file($key)) &&   (time() - filemtime($this->file($key))) / 60  < $this->ttl();
        }

        /**
         *
         * Get the time to live
         *
         * @return int
         *
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function ttl(): int
        {
            return  env('APP_MODE') !== 'dev' ? intval(config('cache','ttl')) * 1440 : intval(config('cache','ttl'));
        }

        /**
         *
         * Get the directory path
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function directory(): string
        {
            return config('cache','directory');
        }

        /**
         * @param string $key
         * @return string
         * @throws Kedavra
         */
        public function file(string $key): string
        {
            return  base($this->directory(),$key);
        }

        /**
         * @inheritDoc
         */
        public function clear(): bool
        {
           $x = function (){ return glob($this->directory() .DIRECTORY_SEPARATOR . '*');};
           foreach ($x() as $file)
               unlink($file);

           return  not_def($x());
        }
    }
}