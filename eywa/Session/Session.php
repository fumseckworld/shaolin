<?php


namespace Eywa\Session {


    class Session implements SessionInterface
    {

        /**
         * @inheritDoc
         */
        public function get(string $key)
        {
            $this->start();
            return $this->has($key) ?  $_SESSION[$key] : '';
        }

        /**
         * @inheritDoc
         */
        public function set(string $key, $value): SessionInterface
        {
            $this->start();
            $_SESSION[$key] = $value;
            return $this;
        }

        /**
         * @inheritDoc
         */
        public function has(string $key): bool
        {
            $this->start();
            return  array_key_exists($key,$_SESSION);
        }

        /**
         * @inheritDoc
         */
        public function destroy(string $key): bool
        {
           if ($this->has($key))
           {
               unset($_SESSION[$key]);
               return true;
           }
           return  false;
        }

        /**
         * @inheritDoc
         */
        public function start(): SessionInterface
        {
            if (php_sapi_name() !== 'cli')
            {
                if (session_status() === PHP_SESSION_NONE)
                    session_start();
            }


            return  $this;
        }

        /**
         * @inheritDoc
         */
        public function all(): array
        {
            $this->start();
            return $_SESSION;
        }

        /**
         * @inheritDoc
         */
        public function clear(): bool
        {
            foreach ($this->all() as $value)
                $this->destroy($value);

            return empty($this->all());
        }
    }
}