<?php

namespace Imperium\Session {


    use Imperium\Collection\Collection;

    class ArraySession implements SessionInterface
    {
        /**
         * @var Collection
         */
        private $session;

        /**
         * Get a session key
         *
         * @param $key
         *
         * @return mixed
         *
         */
        public function get($key)
        {
            return $this->session->get($key);
        }

        /**
         *
         * Check if the session has a key
         *
         * @param $key
         *
         * @return bool
         *
         */
        public function has($key): bool
        {
            return $this->session->has($key);
        }

        /**
         *
         * Define a value
         *
         * @param $key
         * @param $value
         *
         * @return  Collection
         *
         */
        public function put($key, $value): Collection
        {
            return $this->session->put($key,$value);
        }

        /**
         *
         * Remove a key
         *
         * @param $key
         *
         * @return bool
         *
         */
        public function remove($key): bool
        {
            return $this->session->del($key)->key_not_exist($key);
        }

        /**
         *
         * Get all value
         *
         * @return array
         *
         */
        public function all(): array
        {
            return $this->session->all();
        }

        /**
         * SessionInterface constructor.
         */
        public function __construct()
        {
            $this->session = collect();
        }

        /**
         *
         * Set and return value
         *
         * @param $key
         * @param $value
         *
         * @return mixed
         *
         */
        public function def($key, $value)
        {
            return $this->put($key,$value)->get($key);
        }

        /**
         *
         * Clear the session
         *
         * @return bool
         *
         */
        public function clear(): bool
        {
           foreach ($this->all() as $k => $v)
               $this->remove($k);

           return not_def($this->all());
        }
    }
}