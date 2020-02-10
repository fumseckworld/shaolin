<?php


namespace Eywa\Cache;


use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Eywa\Exception\Kedavra;

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
     * @throws Kedavra
     * @throws DependencyException
     * @throws NotFoundException
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
     * @throws Kedavra
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
     * @throws Kedavra
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
     * @throws Kedavra
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
     * @throws Kedavra
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
     * @throws Kedavra
     *
     */
    public function clear(): bool;
}