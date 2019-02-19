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
            $file = 'flash';

            $success_class = collection(config($file,'success'))->get('class');
            $danger_class = collection(config($file,'failure'))->get('class');

            $message = $this->get($key);

            $this->session->remove($key);

            if (def($message))
            {
                if ($success)
                    $html = '<div class="'.$success_class.'" role="alert">'.$message.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                else
                    $html = '<div class="'.$danger_class.'" role="alert">'.$message.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

                return $html;
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