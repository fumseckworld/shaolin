<?php

declare(strict_types=1);

namespace Eywa\Cache {

    use Exception;

    interface CacheInterface
    {


        /**
         *
         * Get a value
         *
         * @param string $key
         *
         * @return mixed
         *
         *
         * @throws Exception
         *
         */
        public function get(string $key);

        /**
         * Set a value
         *
         * @param string $key
         * @param $value
         *
         * @return $this
         *
         * @throws Exception
         *
         */
        public function set(string $key, $value): self;

        /**
         *
         * Remove a value
         *
         * @param string $key
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function destroy(string $key): bool;

        /**
         *
         * Check if a key is define in the cache
         *
         * @param string $key
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has(string $key): bool;

        /**
         *
         * Get time to live
         *
         * @return int
         *
         * @throws Exception
         *
         */
        public function ttl(): int;

        /**
         *
         * Clear all cache
         *
         * @return bool
         *
         */
        public function clear(): bool;
    }
}