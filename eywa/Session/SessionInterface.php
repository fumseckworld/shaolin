<?php

declare(strict_types=1);

namespace Eywa\Session {


    use Eywa\Exception\Kedavra;

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
         * @throws Kedavra
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
         * @param string[] $keys
         * @return bool
         */
        public function destroy(string ...$keys): bool;

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
}