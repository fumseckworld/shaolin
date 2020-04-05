<?php

namespace Eywa\Database\Table {


    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Exception\Kedavra;

    interface Records
    {

        /**
         *
         * Management constructor.
         *
         * @param Connect $connect
         *
         */
        public function __construct(Connect $connect);


        /**
         *
         * Drop a table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function drop(): bool;

        /**
         *
         * Check if a table exist
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function exist(): bool;

        /**
         *
         * Truncate a table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function truncate(): bool;

        /**
         *
         * Remove all columns
         *
         * @param array<string> $columns
         *
         * @return bool
         *
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        public function rename(string $new_name): bool;

        /**
         *
         * Check if the table has columns
         *
         * @param array<string> $columns
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function has(array $columns): bool;

        /**
         *
         * Rename a column
         *
         * @param string $column
         * @param string $new_name
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function renameColumn(string $column, string $new_name): bool;

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
         * Export the database records in a sql file
         *
         * @return bool
         *
         */
        public function export(): bool;

        /**
         *
         * Import sql file content into the database
         *
         * @return bool
         *
         */
        public function import(): bool;


        /**
         *
         * List all tables found
         *
         * @return Collect
         *
         * @throws Kedavra
         *
         */
        public function show(): Collect;

        /**
         *
         * Show all columns inside a table
         *
         * @return Collect
         *
         * @throws Kedavra
         *
         */
        public function columns(): Collect;

        /**
         *
         * Show columns type
         *
         * @return Collect
         *
         */
        public function types(): Collect;

        /**
         *
         * Get an instance of connect
         *
         * @return Connect
         *
         *
         */
        public function connexion(): Connect;
    }
}
