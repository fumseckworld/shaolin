<?php

declare(strict_types=1);
namespace Eywa\Database\Migration {


    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Table\Table;
    use Faker\Generator;

    interface Migrate
    {
        /**
         *
         * select a table
         *
         * @param string $table
         *
         * @return Migrate
         *
         */
        public function for(string $table): Migrate;

        /**
         *
         * Add a column in a table
         *
         * @param string $column
         * @param string $type
         * @param array $options
         *
         * @return Migrate
         *
         */
        public function add(string $column, string $type, array $options = []): Migrate;


        /**
         *
         * Remove the table
         *
         * @return bool
         *
         */
        public function drop(): bool;


        /**
         *
         * Change the table name
         *
         * @param string $new_name
         *
         * @return bool
         *
         */
        public function rename_table(string $new_name): bool;

        /**
         *
         * Rename a column
         *
         * @param string $new_name
         *
         * @return bool
         *
         */
        public function rename_column(string $new_name): bool;

        /**
         *
         * Get an instance of database
         *
         * @return Connect
         *
         */
        public function connect(): Connect;

        /***
         *
         * Remove columns
         *
         * @param string ...$columns
         *
         * @return bool
         *
         */
        public function del(string ...$columns): bool;

        public function up(): bool;

        public function down(): bool;

        /**
         *
         * Get the columns
         *
         * @return array
         *
         */
        public function columns(): array ;

        /**
         *
         * Seed the base
         *
         * @param int $records
         *
         * @return bool
         */
        public function seed(int $records): bool;

        /**
         *
         * Generate records
         *
         * @param Generator $generator
         * @param Table $table
         *
         * @return string
         *
         */
        public function generate(Generator $generator,Table $table) : string;


    }
}