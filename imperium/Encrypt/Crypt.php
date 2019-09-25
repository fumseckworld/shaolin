<?php

namespace Imperium\Encrypt {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Exception;
    use Imperium\Exception\Kedavra;

    class Crypt
    {

        /**
         * The encryption key.
         *
         * @var string
         */
        protected $key;

        /**
         * The algorithm used for encryption.
         *
         * @var string
         */
        protected $cipher;

        /**
         * Create a new encrypter instance.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         */
        public function __construct()
        {

            $this->key = base64_decode(app()->env('APP_KEY'));

            $this->cipher = strtolower(config('encrypt', 'cipher'));

            is_true(collect(openssl_get_cipher_methods())->not_exist($this->cipher),true,"The cipher is not valid");
        }


        /**
         * Create a new encryption key for the given cipher.
         *
         * @return string
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public static function generateKey(): string
        {

            return base64_encode(random_bytes(openssl_cipher_iv_length(strtolower(config('encrypt', 'cipher')))));
        }

        /**
         * Encrypt the given value.
         *
         * @param mixed $value
         * @param bool $serialize
         *
         * @return string
         *
         * @throws Exception
         *
         * @throws Kedavra
         */
        public function encrypt($value, $serialize = true)
        {

            $iv = random_bytes(openssl_cipher_iv_length($this->cipher));

            $value = openssl_encrypt($serialize ? serialize($value) : $value, $this->cipher, $this->key, 0, $iv);

            is_false($value, true, 'Could not encrypt the data.');

            $mac = $this->hash($iv = base64_encode($iv), $value);

            $json = json_encode(compact('iv', 'value', 'mac'));

            different(json_last_error(), JSON_ERROR_NONE, true, 'Could not encrypt the data.');

            return base64_encode($json);
        }

        /**
         * Encrypt a string without serialization.
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
         *
         */
        public function decrypt(string $payload, bool $unserialize = true)
        {

            $payload = $this->getJsonPayload($payload);

            $iv = base64_decode($payload['iv']);

            $decrypted = openssl_decrypt($payload['value'], $this->cipher, $this->key, 0, $iv);

            is_false($decrypted, true, 'could not decrypt the data.');

            return $unserialize ? unserialize($decrypted) : $decrypted;
        }

        /**
         * Decrypt the given string without unserialization.
         *
         * @param string $payload
         *
         * @return string
         *
         * @throws Kedavra
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
         * @return array
         *
         * @throws Exception
         *
         * @throws Kedavra
         */
        protected function getJsonPayload($payload)
        {

            $payload = json_decode(base64_decode($payload), true);

            if (!$this->validPayload($payload))
            {
                throw new Kedavra('The payload is invalid.');
            }
            if (!$this->validMac($payload))
            {
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

            return is_array($payload) && isset($payload['iv'], $payload['value'], $payload['mac']) && strlen(base64_decode($payload['iv'], true)) === openssl_cipher_iv_length($this->cipher);
        }

        /**
         *
         * Determine if the MAC for the given payload is valid.
         *
         * @param array $payload
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        protected function validMac(array $payload)
        {

            $calculated = $this->calculateMac($payload, $bytes = random_bytes(16));

            return hash_equals(hash_hmac('sha256', $payload['mac'], $bytes, true), $calculated);
        }

        /**
         * Calculate the hash of the given payload.
         *
         * @param array $payload
         * @param string $bytes
         *
         * @return string
         */
        protected function calculateMac($payload, $bytes)
        {

            return hash_hmac('sha256', $this->hash($payload['iv'], $payload['value']), $bytes, true);
        }

    }
}
