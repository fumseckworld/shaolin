<?php


namespace Eywa\Session;


interface SessionInterface
{
    /**
     *
     * Get a value in the session
     *
     * @param string $key
     *
     * @return mixed
     *
     */
    public function get(string $key);

    /**
     *
     * Set a value in the session
     *
     * @param string $key
     * @param $value
     *
     * @return $this
     *
     */
    public function set(string $key, $value): self;

    /**
     *
     * Check if a key exist
     *
     * @param string $key
     *
     * @return bool
     *
     */
    public function has(string $key): bool;

    /**
     *
     * Remove a key
     *
     * @param string $key
     *
     * @return bool
     *
     */
    public function destroy(string $key): bool;

    /**
     *
     * Start the session
     *
     * @return $this
     *
     */
    public function start(): self;

    /**
     *
     * Get all values
     *
     * @return array
     *
     */
    public function all(): array;

    /**
     *
     * Clear the sesion
     *
     * @return bool
     *
     */
    public function clear(): bool;
}