<?php

declare(strict_types=1);

namespace Eywa\Database\Connexion {


    use Exception;
    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;


    interface Connexion
    {

        /**
         *
         * Connexion constructor.
         *
         * @param string $driver
         * @param string $base
         * @param int $port
         * @param string $username
         * @param string $password
         * @param string $host
         *
         * @throws Kedavra
         */
        public function __construct(string $driver, string $base,string $username ='',string $password ='',int $port = 3306, string $host = LOCALHOST);

        /**
         *
         * Set the sql queries to execute
         *
         * @param string $query
         *
         * @return Connexion
         *
         */
        public function set(string $query): Connexion;

        /**
         *
         * Get an instance of the development base
         *
         * @return Connect
         *
         * @throws Kedavra
         *
         * @throws Exception
         *
         */
        public function development(): Connect;

        /**
         *
         *
         * @param string $query
         *
         * @return mixed
         *
         * @throws Kedavra
         *
         */
        public function query(string $query);

        /**
         *
         * Add arguments
         *
         * @param mixed ...$args
         *
         * @return Connexion
         *
         */
        public function with(...$args): Connexion;

        /**
         *
         * Execute the sql query
         *
         * Return true on success or false in failure
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function execute(): bool;

        /**
         *
         *
         * @param string $class
         * @param array<mixed> $args
         *
         * @return array<mixed>
         *
         * @throws Kedavra
         *
         */
        public function fetch(string $class = 'stdClass',array $args = []) : array ;


        /**
         *
         * Execute the query and return a collection
         *
         * With all records found by the query
         *
         * @param int $style
         *
         * @return array<mixed>
         *
         * @throws Kedavra
         *
         */
        public function get(int $style ): array ;

        /**
         *
         * Create all users and database for an environement
         *
         * @return bool
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function setup(): bool;

        /**
         *
         * Secure a string
         *
         * @param string $x
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function secure(string $x): string;


        /**
         *
         * Commit the transaction
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function commit(): bool;

        /**
         *
         * Cancel the transaction
         *
         * @param string $message
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function back(string $message): bool;

        /**
         *
         * Check if the driver is available
         *
         * @return boolean
         *
         */
        public function available(): bool;

        /**
         *
         * Get the driver
         *
         * @return string
         *
         */
        public function driver(): string;

        /**
         *
         * Get the base name
         *
         * @return string
         *
         */
        public function base(): string;

        /**
         *
         * Get the username
         *
         * @return string
         *
         */
        public function username(): string;

        /**
         *
         * Get the password
         *
         * @return string
         *
         */
        public function password(): string;

        /**
         *
         * Get the hostname
         *
         * @return string
         *
         */
        public function hostname(): string;

        /**
         *
         * Get the base port
         *
         * @return int
         *
         */
        public function port(): int;

        /**
         *
         *
         * Get the sql
         *
         * @return Collect
         *
         */
        public function sql(): Collect ;
    }
}