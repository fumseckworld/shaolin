<?php


namespace Imperium\Cache {


    class Cache
    {

        /**
         *
         * Check if a key is in the cache
         *
         * @param $key
         *
         * @return bool
         *
         */
        public function has(string $key): bool
        {
            return apcu_exists($key);
        }

        /**
         *
         * Add a value inside cache
         *
         * @param string $key
         * @param $value
         *
         * @return bool
         *
         */
        public function set(string $key,$value): bool
        {
             return apcu_add($key,$value);
        }

        /**
         *
         * Remove a value in the cache
         *
         * @param string $key
         *
         * @return bool|string[]
         *
         */
        public function remove(string $key)
        {
            return apcu_delete($key);
        }

        /**
         *
         * Clear the cache
         *
         * @return bool
         *
         */
        public function clear(): bool
        {
            return apcu_clear_cache();
        }

        /**
         *
         * @param string $key
         *
         * @return mixed
         *
         */
        public function get(string $key)
        {
            return apcu_fetch($key);
        }


    }
}