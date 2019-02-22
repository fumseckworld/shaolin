<?php

namespace Imperium\Session {



    /**
     *
     * Session management
     *
     * @author Willy Micieli <micieli@laposte.net>
     *
     * @package imperium
     *
     * @version 4
     *
     * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE
     *
     **/
    class Session
    {

        public function __construct()
        {
            if (session_status() === PHP_SESSION_NONE)
                session_start();
        }

        /**
         *
         * Get a session value
         *
         * @param mixed $key
         *
         * @return mixed
         *
         */
        public function get($key)
        {
            return $this->has($key) ? $_SESSION[$key] : '';
        }

        /**
         *
         * Check if a key exist
         *
         * @param $key
         *
         * @return bool
         *
         */
        public function has($key): bool
        {
            return array_key_exists($key,$_SESSION);
        }

        /**
         *
         * Add a new value
         *
         * @param $value
         * @param string $key
         *
         * @return Session
         *
         */
        public function set($value,$key): Session
        {
            $_SESSION[$key] = $value;

            return $this;
        }

        /**
         *
         * Remove a value
         *
         * @param mixed $key
         *
         * @return bool
         *
         */
        public function remove($key): bool
        {
            if ($this->has($key))
            {
                unset($_SESSION[$key]);
                return true;
            }
            return false;
        }

        /**
         * @return array
         */
        public function all(): array
        {
            return $_SESSION;
        }


    }
}