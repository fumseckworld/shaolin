<?php

namespace Imperium\Session {


    interface SessionInterface
    {
        /**
         * Get a session key
         *
         * @param $key
         *
         * @return mixed
         *
         */
        public function get($key);

        /**
         *
         * Check if the session has a key
         *
         * @param $key
         *
         * @return bool
         *
         */
        public function has($key): bool;

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
        public function set($key,$value): void ;

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
        public function def($key,$value);

        /**
         *
         * Remove a key
         *
         * @param $key
         *
         * @return bool
         *
         */
        public function remove($key): bool ;

        /**
         *
         * Get all value
         *
         * @return array
         *
         */
        public function all(): array;

        /**
         * SessionInterface constructor.
         */
        public function __construct();

    }
}