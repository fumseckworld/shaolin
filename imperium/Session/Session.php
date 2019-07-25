<?php

namespace Imperium\Session {

    


    /**
     *
     * Session management
     *
     * @author Willy Micieli <micieli@laposte.net>
     *
     * @Injectable
     *
     * @package imperium
     *
     * @version 4
     *
     * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE
     *
     **/
    class Session implements SessionInterface
    {


        private function start()
        {
           if (request()->getScriptName() !== './vendor/bin/phpunit')
           {
               if (session_status() === PHP_SESSION_NONE)
                   session_start();
           }
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
            $this->start();

            return  $this->has($key) ? $_SESSION[$key] : '';
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
            $this->start();
            return array_key_exists($key,$_SESSION);

        }

        /**
         *
         * Add a new value
         *
         * @param $value
         * @param string $key
         *
         * @return void
         *
         */
        public function put($key,$value): void
        {
            $this->start();
            $_SESSION[$key] = $value;

        }

        /**
         *
         * Remove a value
         *
         * @param array $keys
         * @return bool
         */
        public function remove(...$keys): bool
        {
            $this->start();

            foreach ($keys as $key)
            {
                if (array_key_exists($key,$_SESSION))
                    unset($_SESSION[$key]);
            }


            return true;
        }

        /**
         * @return array
         */
        public function all(): array
        {
            $this->start();
            return $_SESSION;
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
            $this->start();
            $_SESSION[$key] = $value;
            return $value;

        }

        /**
         * SessionInterface constructor.
         */
        public function __construct()
        {


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
            $this->start();
            foreach ($this->all() as $k => $v)
               unset($_SESSION[$k]);

            return true;
        }
    }
}