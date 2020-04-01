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
         * @throws Kedavra
         * @throws Exception
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
         * @throws Kedavra
         * @throws Exception
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
         * @throws Exception
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
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function different(string $column, $expected): array;

        /**
         *
         * Get all records
         *
         * @return array<stdClass>
         *
         * @throws Kedavra
         * @throws Exception
         *
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
         * @throws Kedavra
         * @throws Exception
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
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function search(string $value): array;

        /**
         *
         * Get the columns in the table
         *
         * @return array<string>
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function columns(): array;

        /**
         *
         * Get the primary key
         *
         * @return string
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function primary(): string;


        /**
         *
         * Paginate all records
         *
         * @param callable $callback
         * @param int $current_page
         *
         * @return Sql
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function paginate(callable $callback, int $current_page): Sql;
    }
}
