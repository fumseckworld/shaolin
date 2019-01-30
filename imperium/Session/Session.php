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
       
        private function start_session()
        {
            if (session_status() === PHP_SESSION_DISABLED)
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
            $this->start_session();

            return array_key_exists($key,$_SESSION) ? $_SESSION[$key] : '';
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
            $this->start_session();

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
            $this->start_session();

            if (array_key_exists($key,$_SESSION))
            {
                unset($_SESSION[$key]);
                return true;
            }
            return false;
        }

        /**
         *
         * Get all values
         *
         * @return array
         *
         */
        public function all(): array
        {
            return $_SESSION;
        }

    }
}