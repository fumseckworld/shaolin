<?php

namespace Eywa\Database\Migration {

    use Eywa\Exception\Kedavra;

    /**
     * Class Migration
     *
     * @package Eywa\Database\Migration
     *
     */
    abstract class Migration
    {

        /**
         *
         * The creation time of the migration
         *
         */
        public static string $created_at = '';

        /**
         *
         * The table name
         *
         */
        public static string $table = '';

        /**
         *
         * The migration success message
         *
         */
        public static string $up_success_message = '';

        /**
         *
         * The migration error message
         *
         */
        public static string $up_error_message = '';

        /**
         *
         * The rollback success message
         *
         */
        public static string $down_success_message = '';

        /**
         *
         * The rollback error message
         *
         */
        public static string $down_error_message = '';

        /**
         *
         * The migration title
         *
         */
        public static string $up_title = '';

        /**
         *
         * The rollback title
         *
         */
        public static string $down_title = '';

        /**
         *
         * The instance to manage all tables
         *
         */
        private Evolution $db;

        /**
         *
         * The migration up code
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        abstract public function up(): bool;

        /**
         *
         * The migration down code
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        abstract public function down(): bool;

        /**
         *
         * Migration constructor.
         *
         * @param string $env
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $env)
        {
            $this->db = new Evolution($env, static::$table);
        }

        /**
         *
         * Create the table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function create(): bool
        {
            return $this->db->create();
        }

        /**
         *
         * Drop the table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function drop(): bool
        {
            return $this->db->drop();
        }

        /**
         *
         * Truncate the table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function truncate(): bool
        {
            return $this->db->truncate();
        }

        /**
         *
         * Update the table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function update(): bool
        {
            return $this->db->update();
        }

        /**
         *
         * Remove all columns in the table
         *
         * @param array $columns
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function remove(array $columns): bool
        {
            return $this->db->remove($columns);
        }

        /**
         *
         * Rename the table
         *
         * @param string $new_name
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function rename(string $new_name): bool
        {
            return $this->db->rename($new_name);
        }

        /**
         *
         * Rename a column
         *
         * @param string $colunn
         * @param string $new_column_name
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function refresh(string $colunn, string $new_column_name): bool
        {
            return $this->db->refresh($colunn, $new_column_name);
        }

        /**
         *
         * Remove the foreign key
         *
         * @param array $columns
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function removeForeign(array $columns): bool
        {
            return $this->db->removeForeign($columns);
        }

        /**
         *
         * Add a new column
         *
         * @param string $column
         * @param string $type
         * @param int $size
         * @param array $constraints
         *
         * @return Evolution
         *
         * @throws Kedavra
         *
         */
        public function add(string $column, string $type, int $size = 0, array $constraints = []): Evolution
        {
            return $this->db->add($column, $type, $size, $constraints);
        }


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
        public function foreign(
            string $column,
            string $reference,
            string $reference_column,
            string $on = '',
            string $do = ''
        ): Evolution {
            return $this->db->foreign($column, $reference, $reference_column, $on, $do);
        }
    }
}
