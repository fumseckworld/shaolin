<?php


namespace Eywa\Database\Migration {


    use Eywa\Exception\Kedavra;

    interface Evolution
    {
        /**
         * Evolution constructor.
         *
         * @param string $mode
         * @param string $env
         *
         */
        public function __construct(string $mode,string $env);

        /**
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function create(): bool;

        /**
         *
         * Drop the table
         *
         * @return bool
         *
         */
        public function drop(): bool;

        /**
         *
         * Truncate the table
         *
         * @return bool
         *
         */
        public function truncate(): bool;

        /**
         *
         * Update a table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function update(): bool;

        /**
         *
         * Remove all columns
         *
         * @param array<string> $columns
         *
         * @return bool
         *
         */
        public function remove(array $columns): bool;

        /**
         *
         * Rename a table
         *
         * @param string $new_name
         *
         * @return bool
         *
         */
        public function rename(string $new_name): bool;

        /**
         *
         * Rename a column
         *
         * @param string $colunn
         * @param string $new_column_name
         *
         * @return bool
         *
         */
        public function refresh(string $colunn,string $new_column_name): bool;

        /**
         *
         * Remove foreign key columns
         *
         * @param array $columns
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function remove_foreign(array $columns): bool;

        /**
         *
         * Add a column
         *
         * @param string $column
         * @param string $type
         * @param int $size
         * @param array<string> $constraints
         *
         * @return Evolution
         *
         */
        public function add(string $column, string $type,  int $size = 0,array $constraints= []): Evolution;


        /**
         *
         * Add a new foreign key
         *
         * @param string $column
         * @param string $reference
         * @param string $reference_column
         * @param string $on
         * @param string $do
         *
         * @return Evolution
         *
         */
        public function foreign(string $column, string $reference, string $reference_column, string $on = '', string $do =''): Evolution;


    }
}