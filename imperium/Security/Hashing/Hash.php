<?php


namespace Imperium\Security\Hashing {

    use Imperium\Exception\Kedavra;

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
         * The valid hash
         *
         * @var string
         *
         */
        private $valid;

        /**
         *
         * The secret key
         *
         * @var string
         *
         */
        private $secret;

        /**
         *
         * The hash algorithm
         *
         * @var string
         *
         */
        private $algorithm;

        /**
         * @var string
         */
        private $data;

        /**
         *
         * Hash constructor.
         *
         * @param string $data
         *
         * @throws Kedavra
         */
        public function __construct(string $data)
        {
            $this->algorithm = config('hash','algorithm');

            $this->secret = config('hash','secret');

            $this->data = $data;

            not_in(hash_algos(),$this->algorithm,true,"The current algorithm is not supported");

            $this->valid = $this->generate();

        }

        /**
         *
         * Check if the hash is valid
         *
         * @param string $value
         *
         * @return bool
         *
         */
        public function valid(string $value): bool
        {
            return hash_equals($this->valid,$value);
        }

        /**
         *
         * Generate the hash
         *
         * @return string
         *
         */
        public function generate(): string
        {
            return hash_hmac($this->algorithm,$this->data,$this->secret);
        }

    }
}