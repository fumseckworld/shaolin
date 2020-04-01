<?php

declare(strict_types=1);

namespace Eywa\Security\Crypt {

    use Exception;
    use Eywa\Exception\Kedavra;

    class Crypter
    {
        /**
         *
         * The encryption key.
         *
         */
        private string $key;

        /**
         *
         * The algorithm used for encryption.
         *
         */
        private string $cipher ;

        /**
         * Create a new encrypter instance.
         *
         * @throws Kedavra
         * @throws Exception
         */
        public function __construct()
        {
            $this->key = base64_decode(strval(env('APP_KEY', '')));

            $this->cipher = strtolower(strval(env('CIPHER', 'AES-128-CBC')));

            is_true(collect(openssl_get_cipher_methods())->notExist($this->cipher), true, "The cipher is not valid");
        }


        /**
         *
         * Create a new encryption key for the given cipher.
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public static function generateKey(): string
        {
            return base64_encode(
                random_bytes(
                    intval(
                        openssl_cipher_iv_length(
                            strtolower(
                                strval(
                                    env('CIPHER', 'AES-128-CBC')
                                )
                            )
                        )
                    )
                )
            );
        }

        /**
         *
         * Crypter the given value.
         *
         * @param mixed $value
         * @param bool $serialize
         *
         * @return string
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function encrypt($value, $serialize = true)
        {
            $iv = random_bytes(intval(openssl_cipher_iv_length($this->cipher)));

            $value = openssl_encrypt($serialize ? serialize($value) : $value, $this->cipher, $this->key, 0, $iv);

            is_false($value, true, 'Could not encrypt the data.');

            $mac = $this->hash($iv = base64_encode($iv), $value);

            $json = json_encode(compact('iv', 'value', 'mac'));

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Kedavra('Could not encrypt the data.');
            }

            return base64_encode(strval($json));
        }

        /**
         * Crypter a string without serialization.
         *
         * @param string $value
         *
         * @return string
         *
         * @throws Kedavra
         */
        public function encryptString(string $value)
        {
            return $this->encrypt($value, false);
        }

        /**
         *
         * Decrypt the given value.
         *
         * @param string $payload
         * @param bool $unserialize
         *
         * @return mixed
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function decrypt(string $payload, bool $unserialize = true)
        {
            $payload = $this->getJsonPayload($payload);

            $iv = base64_decode($payload['iv']);

            $decrypted = openssl_decrypt($payload['value'], $this->cipher, $this->key, 0, $iv);

            is_false($decrypted, true, 'could not decrypt the data.');

            return $unserialize ? unserialize(strval($decrypted)) : $decrypted;
        }

        /**
         * Decrypt the given string without unserialization.
         *
         * @param string $payload
         *
         * @return string
         *
         * @throws Kedavra
         * @throws Exception
         */
        public function decryptString(string $payload)
        {
            return $this->decrypt($payload, false);
        }

        /**
         * Create a MAC for the given value.
         *
         * @param string $iv
         * @param mixed $value
         *
         * @return string
         */
        protected function hash($iv, $value)
        {
            return hash_hmac('sha256', $iv . $value, $this->key);
        }

        /**
         * Get the JSON array from the given payload.
         *
         * @param string $payload
         *
         * @return array<mixed>
         *
         * @throws Kedavra
         *
         * @throws Exception
         */
        protected function getJsonPayload($payload): array
        {
            $payload = json_decode(base64_decode($payload), true);

            if (!$this->validPayload($payload)) {
                throw new Kedavra('The payload is invalid.');
            }
            if (!$this->validMac($payload)) {
                throw new Kedavra('The MAC is invalid.');
            }

            return $payload;
        }

        /**
         * Verify that the encryption payload is valid.
         *
         * @param mixed $payload
         *
         * @return bool
         */
        protected function validPayload($payload)
        {
            return is_array($payload) && isset($payload['iv'], $payload['value'], $payload['mac'])
                && strlen(strval(base64_decode($payload['iv'], true))) === openssl_cipher_iv_length($this->cipher);
        }

        /**
         *
         * Determine if the MAC for the given payload is valid.
         *
         * @param array<mixed> $payload
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        protected function validMac(array $payload): bool
        {
            $calculated = $this->calculateMac($payload, $bytes = random_bytes(16));

            return hash_equals(hash_hmac('sha256', $payload['mac'], $bytes, true), $calculated);
        }

        /**
         *
         * Calculate the hash of the given payload.
         *
         * @param array<mixed> $payload
         * @param string $bytes
         *
         * @return string
         *
         */
        protected function calculateMac($payload, $bytes): string
        {
            return hash_hmac('sha256', $this->hash($payload['iv'], $payload['value']), $bytes, true);
        }
    }
}
