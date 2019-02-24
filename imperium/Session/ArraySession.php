<?php

namespace Imperium\Session {


    class ArraySession implements SessionInterface
    {
        /**
         * @var \Imperium\Collection\Collection
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
    }
}