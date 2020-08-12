<?php

namespace Imperium\Cache {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Imperium\Exception\Kedavra;

    /**
     * Class CacheAdaptor
     *
     * Group of methods useful for caches systems.
     *
     * @author  Willy Micieli <micieli@outlook.fr>
     * @package Imperium\Cache\CacheApdator
     * @version 12
     * @licence GPL
     *
     */
    abstract class CacheAdaptor
    {
        /**
         * CacheAdaptor constructor.
         */
        abstract public function __construct();

        /**
         *
         * Check if a id has a value inside the cache
         *
         * @param string $id The cache identifier.
         *
         * @throws NotFoundException
         * @throws Exception
         * @throws DependencyException
         *
         * @return bool
         *
         */
        abstract public function has(string $id): bool;

        /**
         *
         * Get a cached value by this key.
         *
         * @param string $id The cache identifier.
         *
         * @throws NotFoundException
         * @throws Exception
         * @throws DependencyException
         *
         * @return mixed
         *
         */
        abstract public function get(string $id);

        /**
         *
         * Destroy a value in the cache by this identifier.
         *
         * @param string $id The cache identifier.
         *
         * @throws NotFoundException
         * @throws Exception
         * @throws DependencyException
         *
         * @return bool
         *
         */
        abstract public function del(string $id): bool;

        /**
         *
         * Add the value in the cache on success.
         *
         * @param string $id    The cache identifier.
         * @param mixed  $value The expected value to store.
         * @param int    $ttl   The time to leave.
         *
         * @throws Kedavra
         * @throws NotFoundException
         * @throws Exception
         * @throws DependencyException
         *
         * @return CacheAdaptor
         *
         */
        abstract public function add(string $id, $value, int $ttl = 3600): CacheAdaptor;
    }
}
