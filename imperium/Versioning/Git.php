<?php

    namespace Imperium\Versioning;

    use Imperium\Redis\Redis;

    class Git
    {
        /**
         *
         * Instance of redis
         *
         * @var Redis
         *
         */
        private $redis;

        /**
         *
         * The repository name
         *
         * @var string
         *
         */
        private $repository;

        /**
         *
         * The repository owner
         *
         * @var string
         *
         */
        private $owner;

        /**
         * Git constructor.
         *
         * @param string $repository
         * @param string $owner
         *
         */
        public function __construct(string $repository,string $owner)
        {
            $this->redis = new Redis();
            $this->repository = $repository;
            $this->owner = $owner;
        }
    }