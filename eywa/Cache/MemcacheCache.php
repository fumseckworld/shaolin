<?php

declare(strict_types=1);

namespace Eywa\Cache {

    use Memcache;

    class MemcacheCache implements CacheInterface
    {

        /**
         *
         */
        private Memcache $cache;

        public function __construct(string $host ='localhost',int $port = 11211,bool $persistent =true)
        {
            $this->cache = new Memcache();
            $this->cache->addServer($host,$port,$persistent);

        }

        /**
         * @inheritDoc
         */
        public function get(string $key)
        {
            return $this->cache->get($key);
        }

        /**
         * @inheritDoc
         */
        public function set(string $key, $value): CacheInterface
        {
            $this->cache->set($key,$value,MEMCACHE_COMPRESSED,$this->ttl());

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function destroy(string $key): bool
        {
           return $this->has($key) ? $this->cache->delete($key): false;

        }

        /**
         * @inheritDoc
         */
        public function has(string $key): bool
        {
            return def($this->cache->get($key));
        }

        /**
         * @inheritDoc
         */
        public function ttl(): int
        {
            return  env('APP_MODE') !== 'dev' ? intval(config('cache','ttl')) * 1440 : intval(config('cache','ttl'));
        }

        /**
         * @inheritDoc
         */
        public function clear(): bool
        {
            return $this->cache->flush();
        }
    }
}