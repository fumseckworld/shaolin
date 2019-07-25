<?php

namespace Imperium\Session {

    use Imperium\Collection\Collection;


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

        /**
         * @var Collection
         */
        private $session;

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
            return  $this->session->get($key);
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
            return $this->session->has($key);
        }

        /**
         *
         * Add a new value
         *
         * @param $value
         * @param string $key
         *
         * @return Collection
         *
         */
        public function put($key,$value): Collection
        {
            return  $this->session->put($key,$value);
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
            return $this->has($key) ?   $this->session->del($key)->key_not_exist($key) : false;
        }

        /**
         * @return array
         */
        public function all(): array
        {
            return $this->session->all();
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
            return $this->session->put($key,$value)->get($key);
        }

        /**
         * SessionInterface constructor.
         */
        public function __construct()
        {
            $this->start();
            $this->session = collect($_SESSION);
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
               $this->session->del($k);

            return $this->session->empty();
        }
    }
}