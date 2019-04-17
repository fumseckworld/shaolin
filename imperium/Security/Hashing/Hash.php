<?php


namespace Imperium\Security\Hashing {

    use Exception;

    /**
     *
     * Management of password
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
    class Hash
    {

        /**
         *
         * The cost
         *
         * @var int
         *
         */
        const COST = 10;


        /**
         * Hash the given value.
         *
         * @param  string $value
         * @return string
         *
         * @throws Exception
         *
         */
        public static function make($value)
        {
            $hash = password_hash($value, PASSWORD_BCRYPT, self::cost());

            is_false($hash,true,'Bcrypt hashing not supported.');

            return $hash;
        }

        /**
         *
         * Check if the given hash has been hashed using the given options.
         *
         * @param string $hashedValue
         *
         * @return bool
         */
        public static function need_rehash(string $hashedValue): bool
        {
            return password_needs_rehash($hashedValue, PASSWORD_BCRYPT, self::cost());
        }

        /**
         *
         * Check if the password is correct
         *
         * @param string $value
         * @param string $hash
         *
         * @return bool
         *
         **/
        public static function verify(string $value,string $hash): bool
        {
            return strlen($value) === 0 ? false : password_verify($value,$hash);
        }

        /**
         * Extract the cost value from the options array.
         *
         * @return array
         *
         */
        private static function cost(): array
        {
            return ['cost' => self::COST];
        }
    }
}