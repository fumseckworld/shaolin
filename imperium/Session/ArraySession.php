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
            return $this->session->has_key($key);
        }

        /**
         *
         * Define a value
         *
         * @param $key
         * @param $value
         *
         * @return  void
         *
         */
        public function set($key, $value): void
        {
            $this->session->add($value,$key);
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
            return $this->session->remove($key)->not_exist($key);
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
            return $this->session->collection();
        }

        /**
         * SessionInterface constructor.
         */
        public function __construct()
        {
            $this->session = collection();
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
           $this->set($key,$value);
           return $this->get($key);
        }
    }
}