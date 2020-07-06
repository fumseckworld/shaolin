<?php

declare(strict_types=1);

namespace Imperium\Security\Hashing {

    /**
     *
     * Represent all hashed values.
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
         * @param string $data The value to use.
         *
         */
        public function __construct(string $data)
        {
            $this->algorithm = strtolower(config('hash', 'algorithm', 'sha512'));

            $this->secret = config('hash', 'secret', env('SECURE_KEY', 'secret'));

            $this->data = $data;

            $this->valid = hash_hmac($this->algorithm, $this->data, $this->secret);
        }

        /**
         *
         * Check if the hash is valid.
         *
         * @param  string  $value The value to analyse.
         *
         * @return bool
         *
         */
        public function valid(string $value): bool
        {
            return hash_equals($this->generate(), $value);
        }

        /**
         *
         * Get the encrypted value.
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
