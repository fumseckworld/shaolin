<?php

namespace Eywa\Database\Query {


    use Eywa\Database\Connexion\Connect;
    use Eywa\Exception\Kedavra;
    use stdClass;

    interface Records
    {


        /**
         *
         * constructor.
         *
         * @param Connect $connect
         * @param string $table
         *
         */
        public function __construct(Connect $connect, string $table);

        /**
         *
         * Add a limit
         *
         * @param int $limit
         * @param int $offset
         *
         * @return self
         *
         * @throws Kedavra
         *
         *
         */
        public function take(int $limit, int $offset = 0): self;

        /**
         *
         * Add an order by
         *
         * @param string $column
         * @param string $order
         *
         * @return self
         *
         */
        public function by(string $column, string $order = DESC): self;

        /**
         *
         * Generate a join clause
         *
         * @param string $type
         * @param string $condition
         * @param string $first_table
         * @param string $second_table
         * @param string $first_param
         * @param string $second_param
         *
         * @return self
         *
         * @throws Kedavra
         *
         */
        public function join(
            string $type,
            string $condition,
            string $first_table,
            string $second_table,
            string $first_param,
            string $second_param
        ): self;

        /**
         *
         * Generate an union clause
         *
         * @param string $type
         * @param string $first_table
         * @param string $second_table
         * @param string $first_column
         * @param string $second_column
         *
         * @return self
         *
         * @throws Kedavra
         *
         */
        public function union(
            string $type,
            string $first_table,
            string $second_table,
            string $first_column,
            string $second_column
        ): self;

        /**
         *
         * Add an or clause
         *
         * @param string $column
         * @param string $condition
         * @param mixed  $expected
         *
         * @return self
         *
         * @throws Kedavra
         *
         *
         */
        public function or(string $column, string $condition, $expected): self;

        /**
         *
         * Add an and clause
         *
         * @param string $column
         * @param string $condition
         * @param mixed  $expected
         *
         * @return self
         *
         * @throws Kedavra
         *
         */
        public function and(string $column, string $condition, $expected): self;

        /**
         *
         * Add a between clause
         *
         * @param string $column
         * @param int $min
         * @param int $max
         *
         * @return self
         *
         */
        public function between(string $column, int $min, int $max): self;
        /**
         *
         * Get only column values
         *
         * @param array<string> $columns
         *
         * @return self
         *
         */
        public function only(array $columns): self;

        /**
         *
         * Add a where clause
         *
         * @param string $column
         * @param string $condition
         * @param mixed  $expected
         *
         * @return self
         *
         * @throws Kedavra
         *
         */
        public function where(string $column, string $condition, $expected): self;

        /**
         *
         * Add a like clause
         *
         * @param string $search
         *
         * @return self
         *
         * @throws Kedavra
         *
         */
        public function like(string $search): self;

        /**
         *
         * Paginate the records
         *
         * @param callable $callback
         * @param int $current_page
         * @param int $limit
         *
         * @return self
         *
         * @throws Kedavra
         *
         */
        public function paginate(callable $callback, int $current_page, int $limit = 20): self;

        /**
         *
         * Check if a resul has been found
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function exist(): bool;

        /**
         *
         * Get the sql query
         *
         * @return string
         *
         */
        public function sql(): string;

        /**
         *
         * Get the primary key
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function primary(): string;

        /**
         *
         * Get the pagination
         *
         * @return string
         *
         */
        public function pagination(): string;

        /**
         *
         * Get the records paginated
         *
         * @return string
         *
         */
        public function records(): string;

        /**
         *
         * Get the result
         *
         * @return array<stdClass>
         *
         * @throws Kedavra
         *
         */
        public function get(): array;

        /**
         *
         *
         * @param int $mode
         *
         * @return array<mixed>
         *
         * @throws Kedavra
         *
         */
        public function to(int $mode): array;

        /**
         *
         * Get the table columns
         *
         * @return array<string>
         *
         * @throws Kedavra
         *
         */
        public function columns(): array;
        /**
         *
         * Execute the query
         *
         * @return bool
         *
         * @throws Kedavra
         *
         *
         */
        public function execute(): bool;

        /**
         *
         * Execute a delete query
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function delete(): bool;

        /**
         *
         * Update a record
         *
         * @param int $id
         * @param array<mixed> $values
         *
         * @return bool
         *
         * @throws Kedavra
         *
         *
         */
        public function update(int $id, array $values): bool;

        /**
         *
         * Create a new record
         *
         * @param array<mixed> $record
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function create(array $record): bool;

        /**
         *
         * Count all record found
         *
         * @return int
         *
         * @throws Kedavra
         *
         */
        public function sum(): int;

        /**
         *
         * @return Connect
         *
         * @throws Kedavra
         *
         */
        public function connexion(): Connect;
    }
}
