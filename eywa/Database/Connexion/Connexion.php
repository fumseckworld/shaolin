<?php


namespace Eywa\Database\Connexion {


    use Eywa\Collection\Collect;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use stdClass;


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
         * @param array $options
         * @param string $host
         *
         * @throws Kedavra
         */
        public function __construct(string $driver, string $base,string $username ='',string $password ='',int $port = 3306, array $options = [], string $host = LOCALHOST);
        /**
         *
         * Set the sql queries to execute
         *
         * @param string[] $queries
         *
         * @return Connexion
         *
         */
        public function set(string ...$queries): Connexion;

        /**
         *
         * Execute the sql query
         *
         * Return true on success or false in failure
         *
         * @param array $args
         *
         * @return bool
         */
        public function execute(array $args = []): bool;

        /**
         *
         *
         * @param string $class
         * @param array $args
         *
         * @return array
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
         * @param array $args
         *
         * @return array
         */
        public function get(int $style ,array $args = []): array ;

        /**
         *
         * Secure a string
         *
         * @param string $x
         *
         * @return string
         *
         */
        public function secure(string $x): string;


        /**
         *
         * Commit the transaction
         *
         * @return bool
         *
         */
        public function commit(): bool;

        /**
         *
         * Cancel the transaction
         *
         * @return bool
         *
         */
        public function back(): bool;

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