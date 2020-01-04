<?php


namespace Eywa\Cache {



    class ApcuCache implements CacheInterface
    {

        /**
         * @inheritDoc
         */
        public function get(string $key)
        {
            return $this->has($key) ? apcu_fetch($key) : false;
        }

        /**
         * @inheritDoc
         */
        public function set(string $key, $value): CacheInterface
        {
            apcu_add($key,$value,$this->ttl());
            return  $this;
        }

        /**
         * @inheritDoc
         */
        public function destroy(string $key): bool
        {
            return $this->has($key) ? apcu_delete($key) : false;
        }

        /**
         * @inheritDoc
         */
        public function has(string $key): bool
        {
            return  apcu_exists($key);
        }

        /**
         * @inheritDoc
         */
        public function ttl(): int
        {
            return  env('APP_MODE') !== 'dev' ? intval(config('cache','ttl')) * 10080 : intval(config('cache','ttl'));
        }

        /**
         * @inheritDoc
         */
        public function clear(): bool
        {
           return  apcu_clear_cache();
        }
    }
}