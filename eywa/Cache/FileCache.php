<?php

declare(strict_types=1);

namespace Eywa\Cache {

    use Eywa\File\File;

    class FileCache implements CacheInterface
    {

        /**
         * @inheritDoc
         */
        public function get(string $key)
        {
            if ($this->has($key)) {
                return (new File($this->file($key)))->read();
            }
            return false;
        }

        /**
         * @inheritDoc
         */
        public function set(string $key, $value): CacheInterface
        {
            (new File($this->file($key), EMPTY_AND_WRITE_FILE_MODE))->write($value)->flush();

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
            $x = env('CACHE_TIME_DIVISER', 60);
            $x = $x <= 0 ? 1 : $x;

            return file_exists($this->file($key)) &&   (time() - filemtime($this->file($key))) / $x  < $this->ttl();
        }

        /**
         * @inheritDoc
         */
        public function ttl(): int
        {
            return  intval(env('CACHE_TTL', CACHE_DEFAULT_TTL));
        }


        /**
         * @inheritDoc
         */
        public function directory(): string
        {
            return 'cache';
        }


        /**
         * @inheritDoc
         */
        public function file(string $key): string
        {
            return  base($this->directory(), $key);
        }

        /**
         * @inheritDoc
         */
        public function clear(): bool
        {
            $x = function () {
                return glob($this->directory() .DIRECTORY_SEPARATOR . '*');
            };
            foreach ($x() as $file) {
                unlink($file);
            }

            return  not_def($x());
        }
    }
}
