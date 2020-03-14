<?php

declare(strict_types=1);
namespace Eywa\Session {


    use Eywa\Collection\Collect;

    class ArraySession implements SessionInterface
    {

        /**
         *
         * The session
         *
         */
        private Collect $session;

        public function __construct()
        {
            $this->session = collect();
        }

        /**
         * @inheritDoc
         */
        public function get(string $key)
        {
            return $this->session->get($key);
        }

        /**
         * @inheritDoc
         */
        public function set(string $key, $value): SessionInterface
        {
            $this->session->put($key,$value);
            return $this;
        }

        /**
         * @inheritDoc
         */
        public function has(string $key): bool
        {
            return  $this->session->has($key);
        }

        /**
         * @inheritDoc
         */
        public function destroy(array $keys): bool
        {
            return $this->session->del($keys)->ok();
        }

        /**
         * @inheritDoc
         */
        public function start(): SessionInterface
        {
            return  $this;
        }

        /**
         * @inheritDoc
         */
        public function all(): array
        {
            return $this->session->all();
        }

        /**
         * @inheritDoc
         */
        public function clear(): bool
        {
            return  $this->session->del($this->all())->ok();
        }
    }
}