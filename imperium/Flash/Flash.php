<?php

namespace Imperium\Flash {


    use Exception;
    use Imperium\Session\ArraySession;
    use Imperium\Session\Session;
    use Imperium\Session\SessionInterface;

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
        const SUCCESS_KEY = 'success';
        const FAILURE_KEY = 'failure';
        const VALID = [self::SUCCESS_KEY,self::FAILURE_KEY];


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
         *
         * @param SessionInterface $session
         *
         */
        public function __construct(SessionInterface $session)
        {
            $this->session = $session;
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
            $this->session->set(self::SUCCESS_KEY,$message);
        }

        /**
         *
         * Check if a key is defined
         *
         * @param string $key
         *
         * @return bool
         *
         */
        public function has(string $key): bool
        {
            return def($this->session->get($key));
        }


        /**
         *
         * Generate a bootstrap alert
         *
         * @param string $key
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function display(string $key): string
        {
            $success  = equal($key,self::SUCCESS_KEY);

            $message = $this->get($key);

            $this->session->remove($key);

            if (def($message))
            {
                return $success ?  '<div class="row"><div class="column"><div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-5" role="alert"><div class="flex"><p class="font-bold">'.$message.'</p></div></div></div></div>' : '<div class="row"><div class="column"><div class="bg-red-300 border-t-4 border-red-500 rounded-b text-red-800 px-4 py-3 shadow-md mb-5" role="alert"><div class="flex"><p class="font-bold">'.$message.'</p></div></div></div></div>';

            }

            return '';
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
            $this->session->set(self::FAILURE_KEY,$message);
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