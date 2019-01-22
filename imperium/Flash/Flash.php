<?php

namespace Imperium\Flash {


    use Exception;
    use Imperium\Session\Session;

    /**
     *
     * Management of the flash message
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
    class Flash
    {
        /**
         *
         * The success key
         *
         * @var string
         *
         */
        const SUCCESS_KEY   = 'success';

        /**
         *
         * The failure key
         *
         * @var string
         *
         */
        const FAILURE_KEY   = 'failure';

        /**
         *
         * All valid get type keys
         *
         * @var array
         *
         */
        const VALID =
        [
            self::SUCCESS_KEY,
            self::FAILURE_KEY
        ];

        /**
         * @var Session
         */
        private $session;

        /**
         * @var string
         */
        private $flash;

        /**
         *
         * Flash constructor
         *
         */
        public function __construct()
        {
            $this->session = new Session();
        }

        /**
         *
         * Add a success message
         *
         * @param string $message
         *
         * @return void
         *
         */
        public function success(string $message): void
        {
            $this->session->set($message,self::SUCCESS_KEY);
        }

        /**
         *
         * Add a failure message
         *
         * @param string $message
         *
         * @return  void
         *
         */
        public function failure(string $message): void
        {
            $this->session->set($message,self::FAILURE_KEY);
        }

        /**
         *
         * Get the value
         *
         * @param string $key
         * @return string
         *
         * @throws Exception
         */
        public function get(string $key): string
        {
            not_in(self::VALID,$key,true,"The current key is not valid");

            $this->flash = $this->session->get($key);

            $this->session->remove($key);

            return $this->flash;
        }


    }
}