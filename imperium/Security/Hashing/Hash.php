<?php

declare(strict_types=1);

namespace Imperium\Security\Hashing {

    use Imperium\Exception\Kedavra;

    /**
     *
     * @property string $valid      The valid hash.
     * @property string $secret     The secret key.
     * @property string $algorithm  The algorithm used to hash password.
     * @property string $data       The user's data.
     *
     */
    class Hash
    {


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
            if (!in_array($this->algorithm, hash_algos())) {
                throw new Kedavra('The algorithm used is not a valid algorithm');
            }

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
        public function valid(string $value): bool
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
        public function generate(): string
        {
            return $this->valid;
        }
    }
}
