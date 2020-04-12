<?php

namespace Eywa\Database\Model {


    use Exception;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use stdClass;

    interface Interact
    {

        /**
         * Delete a record
         *
         * @param int $id
         *
         * @return bool
         *
         */
        public function destroy(int $id): bool;

        /**
         *
         * Update records
         *
         * @param int $id
         * @param array<mixed> $values
         *
         * @return bool
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
         *
         */
        public function create(array $record): bool;

        /**
         *
         * Find a record
         *
         * @param int $id
         *
         * @return stdClass
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function find(int $id): stdClass;


        /**
         *
         * Get record by a difference
         *
         * @param string $column
         * @param mixed  $expected
         *
         * @return array<stdClass>
         *
         */
        public function different(string $column, $expected): array;

        /**
         *
         * Get all records
         *
         * @return array<stdClass>
         *
         */
        public function all(): array;

        /**
         *
         * Find a record by a column
         *
         * @param string $column
         * @param mixed $expected
         *
         * @return stdClass
         *
         */
        public function by(string $column, $expected): stdClass;

        /**
         *
         * The search value
         *
         * @param string $value
         *
         * @return array<stdClass>
         *
         */
        public function search(string $value): array;

        /**
         *
         * Get the columns in the table
         *
         * @return array<string>
         *
         */
        public function columns(): array;

        /**
         *
         * Get the primary key
         *
         * @return string
         *
         */
        public function primary(): string;


        /**
         *
         * Paginate all records
         *
         * @param callable $callback
         * @param string $slug
         * @param int $current_page
         *
         * @return Sql
         *
         */
        public function paginate(callable $callback, string $slug, int $current_page): Sql;
    }
}
