<?php

namespace Eywa\Security\Hashing
{

    use Eywa\Exception\Kedavra;

    /**
     * Class Hash
     *
     * @author  Willy Micieli
     *
     * @package Imperium\Security\Hashing
     *
     * @license GPL
     *
     * @version 10
     *
     */
    class Hash
    {

        /**
         *
         * The valid hash
         *
         */
        private string $valid;

        /**
         *
         * The secret key
         *
         */
        private string $secret;

        /**
         *
         * The hash algorithm
         *
         */
        private string $algorithm;

        /**
         *
         */
        private string $data;

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

            $this->algorithm = config('hash', 'algorithm');

            $this->secret = config('hash', 'secret');

            $this->data = $data;

            not_in(hash_algos(), $this->algorithm, true, "The current algorithm is not supported");

            $this->valid = hash_hmac($this->algorithm, $this->data, $this->secret);
        }

        /**
         *
         * Check if the hash is valid
         *
         * @param  string  $value
         *
         * @return bool
         *
         */
        public function valid(string $value) : bool
        {
            return hash_equals($this->valid, $value);
        }

        /**
         *
         * Generate the hash
         *
         * @return string
         *
         */
        public function generate() : string
        {
            return $this->valid;
        }

    }
}