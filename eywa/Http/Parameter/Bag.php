<?php

declare(strict_types=1);

namespace Eywa\Http\Parameter {

    use Eywa\Collection\Collect;

    class Bag
    {
        /**
         *
         * The data
         *
         */
        private Collect $data;

        /**
         *
         * Bag constructor.
         *
         * @param array<mixed> $data
         *
         */
        public function __construct(array $data)
        {
            $this->data = collect($data);
        }

        /**
         *
         * Get all results
         *
         * @return array<mixed>
         *
         */
        public function all(): array
        {
            return $this->data->all();
        }

        /**
         *
         * Get a value by a key
         *
         * @param string $key
         * @param mixed $default
         *
         * @return mixed|string|null
         *
         *
         */
        public function get(string $key, $default = null)
        {
            return $this->has($key) ? $this->data->get($key) : $default;
        }

        /**
         *
         * Check if a key exist
         *
         * @param string $key
         *
         * @return bool
         *
         */
        public function has(string $key): bool
        {
            return $this->data->has($key);
        }
    }
}
