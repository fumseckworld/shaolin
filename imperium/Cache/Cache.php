<?php


namespace Imperium\Cache {


    use Imperium\Collection\Collect;

	/**
	 *
	 * Class Cache
	 *
	 * @package Imperium\Cache
	 *
	 * @author Willy Micieli
	 *
	 * @license GPL
	 *
	 * @version 10
	 *
	 */
    class Cache
    {

        /**
         *
         * Check if a key is in the cache
         *
		 * @method has
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
         * Get cache infos
         *
		 * @method infos
		 *
         * @return Collect
         *
         */
        public function infos(): Collect
        {
            return collect(apcu_cache_info());
        }

        /**
         *
         * Check if the key has not value
         *
		 * @method not
		 *
         * @param string $key
         *
         * @return bool
         *
         */
        public function not(string $key): bool
        {
            return ! $this->has($key);
        }

        /**
         *
         * Add a value inside cache
         *
		 * @method set
		 *
         * @param string $key
         * @param $value
         * @param int $ttl
         *
         * @return bool
         *
         */
        public function set(string $key,$value,int $ttl=0): bool
        {
             return apcu_add($key,$value,$ttl);
        }

        /**
         *
         * Remove a value in the cache
         *
		 * @method remove
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
		 * @method clear
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
         * Save a value only if are not already defined
         *
		 * @method def
		 *
         * @param string $key
         * @param $value
         * @param int $ttl
         *
         * @return bool
         *
         */
        public function def(string $key,$value,int $ttl=0): bool
        {
            return $this->not($key) ? $this->set($key,$value,$ttl) : false;
        }


        /**
         *
		 * Get a value
		 *
		 * @method get
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