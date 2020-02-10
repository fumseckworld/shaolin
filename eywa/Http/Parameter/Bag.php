<?php


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
         * @param array $data
         *
         */
        public function __construct(array $data)
        {
            $this->data = collect($data)->for([$this,'secure']);
        }

        /**
         *
         * Get all results
         *
         * @return array
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
         * @param null $default
         *
         * @return mixed|string|null
         *
         */
        public function get(string $key,$default = null)
        {
            return $this->has($key) ? $this->secure($this->data->get($key)) : $default;
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


        /**
         *
         * Secure a string
         *
         * @param mixed $x
         *
         * @return string
         *
         */
        public function secure($x): string
        {
            if (!is_string($x))
              return  '';
            return htmlentities($x,ENT_QUOTES,'UTF-8');
        }

    }
}