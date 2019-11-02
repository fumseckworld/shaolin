<?php


namespace Imperium\Cookies {


    class Cookies
    {

        /**
         * Create a cookie
         *
         * @param string $name
         * @param string|null $value
         * @param int $expire
         * @param string|null $path
         * @param string|null $domain
         * @param bool|null $secure
         * @param bool $httpOnly
         *
         * @return bool
         *
         */
        public function create(string $name, string $value = null, int $expire = 0, ?string $path = '/', string $domain = null, ?bool $secure = false, bool $httpOnly = true): bool
        {

            return setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
        }

        /**
         *
         * Get all cookies
         *
         * @return array
         *
         */
        public function all(): array
        {
            return request()->cookies->all();
        }

        /**
         *
         * List cookies keys
         *
         * @return array
         *
         */
        public function keys(): array
        {
            return request()->cookies->keys();
        }

        /**
         *
         * Return the cookies size
         *
         * @return int
         *
         */
        public function size(): int
        {
            return request()->cookies->count();
        }

        /**
         *
         * Check if key exits
         *
         * @param $key
         *
         * @return bool
         *
         */
        public function has(string $key): bool
        {
            return request()->cookies->has($key);

        }

        /**
         *
         * @param $key
         * @param $value
         *
         * @param int $expire
         * @return Cookies
         */
        public function add($key,$value,int $expire = 0): Cookies
        {
           $this->create($key,$value,$expire);

            return $this;
        }
        /**
         *
         * Check if a key is define
         *
         * @param $key
         *
         * @return bool
         *
         */
        public function def($key): bool
        {
            return def($this->get($key));
        }

        /**
         *
         * Remove a cookie
         *
         * @param string $name
         *
         * @return bool
         *
         */
        public function del(string $name): bool
        {
            return $this->create($name, '', time() - 10);
        }

        /**
         *
         * Get a value
         *
         * @param $key
         * @param null $default
         *
         * @return mixed
         *
         */
        public function get($key, $default = null)
        {
            return request()->cookies->get($key, $default);
        }
    }
}