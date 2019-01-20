<?php

namespace Imperium\Session {

    use Exception;
    use Imperium\Collection\Collection;

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
        /**
         *
         * All data
         *
         * @var Collection
         *
         */
        private $data;

        /**
         * Session constructor
         *
         */
        public function __construct()
        {
            $this->data = collection();

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
            return $this->data->get($key);
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
        public function set($value,$key = ''): Session
        {
            $this->data->add($value,$key);

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
         * @throws Exception
         *
         */
        public function remove($key): bool
        {
           return $this->data->remove($key)->not_exist($key);
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
            return $this->data->collection();
        }

    }
}