<?php

declare(strict_types=1);

namespace Eywa\Cache {
    
    use Redis;

    class RedisCache implements CacheInterface
    {
        /**
         * @var Redis
         */
        private Redis $redis;

        public function __construct(string $host = 'localhost',int $port =6379)
        {
            $this->redis = new Redis();
            $this->redis->connect($host,$port);
        }

        /**
         * @inheritDoc
         */
        public function get(string $key)
        {
            return $this->redis->get($key);
        }

        /**
         * @inheritDoc
         */
        public function set(string $key, $value): CacheInterface
        {
            $this->redis->set($key,$value);

            return $this;
        }

        /**
         * @inheritDoc
         */
        public function destroy(string $key): bool
        {
           return $this->has($key) ? $this->redis->del($key) === 1 : false;

        }

        /**
         * @inheritDoc
         */
        public function has(string $key): bool
        {
            return $this->redis->exists($key) !== 0;
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
           return  $this->redis->flushAll();
        }
    }
}