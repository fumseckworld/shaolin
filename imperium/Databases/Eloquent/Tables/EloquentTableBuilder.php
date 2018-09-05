<?php
/**
 * fumseck added EloquentBuilder.php to imperium
 * The 09/09/17 at 19:41
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package : imperium
 * @author  : fumseck
 */

namespace Imperium\Databases\Eloquent\Tables {


    use Imperium\Connexion\Connect;
    use Imperium\Databases\Eloquent\Eloquent;


    /**
     * Interface EloquentTableBuilder
     *
     * @package Imperium\Eloquent
     */
    interface EloquentTableBuilder
    {

        /**
         * Table constructor.
         *
         * @param Connect $connect
         */
        public function __construct(Connect $connect);



        /**
         * update a table
         *
         * @param int         $id
         * @param array       $values
         * @param array       $ignore
         * @param string|null $table
         *
         * @return bool
         */
        public function update(int $id,array $values,array $ignore= [],string $table = ''): bool;

        /**
         * set hidden tables
         *
         * @param array $hidden
         *
         * @return Table
         */
        public function hidden(array $hidden): Table;

        /**
         * define name of table
         *
         * @param string $current_table_name
         *
         * @return Table
         */
        public function set_current_table(string $current_table_name): Table;

        /**
         * define new name of table
         *
         * @param string $new_name
         *
         * @return Table
         */
        public function set_new_name(string $new_name): Table;


        /**
         * rename a table
         *
         * @return bool
         */
        public function rename(): bool;

        /**
         * check if current database has table
         *
         * @return bool
         */
        public function has(): bool;

        /**
         * check if table has a special column
         *
         * @param string $column
         *
         * @return bool
         */
        public function has_column(string $column): bool;


        /**
         * get columns types
         *
         * @return array
         */
        public function get_columns_types(): array;

        /**
         * get all columns in a table
         *
         * @return array
         */
        public function get_columns(): array;


        /**
         * delete a table
         *
         * @param string $table
         *
         * @return bool
         */
        public function drop(string $table = ''): bool;

        /**
         * delete all tables not ignored in database
         *
         * @return bool
         */
        public function drop_all_tables(): bool;

        /**
         * truncate one or all tables
         *
         * @param string      $table
         * @param int         $mode
         *
         * @return bool
         */
        public function truncate(string $table = '', int $mode = Eloquent::MODE_ONE_TABLE): bool;

        /**
         * add a new field in create task
         *
         * @param string $type
         * @param string $name
         * @param bool   $primary
         * @param int    $length
         * @param bool   $unique
         * @param bool   $nullable
         *
         * @return Table
         */
        public function append_field(string $type, string $name, bool $primary = false, int $length = 0, bool $unique = true, bool $nullable = false): Table;

        /**
         * add a column in a existing table
         *
         * @param string $column
         * @param string $type
         * @param int    $size
         * @param bool   $unique
         * @param bool   $nullable
         *
         * @return bool
         */
        public function append_column(string $column, string $type, int $size = 0, bool $unique = true, bool $nullable = false): bool;

        /**
         * create the table
         *
         * @return bool
         */
        public function create() : bool;

        /**
         * set the engine
         *
         * @param string $engine
         *
         * @return Table
         */
        public function set_engine(string $engine): Table;

        /**
         * return create table sql query
         *
         * @return string
         */
        public function get(): string;

        /**
         * dump a table
         *
         * @param string $table
         *
         * @return bool
         */
        public function dump(string $table = ""): bool;

        /**
         * get the primary key of a table
         *
         * @return string
         */
        public function get_primary_key(): string ;

        /**
         * check if a table is empty
         *
         * @return bool
         */
        public function is_empty(): bool;

        /**
         * select a record by id
         *
         * @param int $id
         *
         * @return array
         */
        public function select_by_id(int $id): array;

        /**
         * delete a record by id
         *
         * @param int $id
         *
         * @return bool
         */
        public function delete_by_id(int $id): bool;

        /**
         * rename a existing column
         *
         * @param string $old
         * @param string $new
         *
         * @return bool
         */
        public function rename_column(string $old, string $new): bool;

        /**
         * delete a column
         *
         * @param string $column
         *
         * @return bool
         */
        public function deleteColumn(string $column): bool;

        /**
         * check if a table exist
         *
         * @param string $table
         *
         * @return bool
         */
        public function exist(string $table = ''): bool;

        /**
         * insert values
         *
         * @param array       $values
         * @param array       $toIgnore
         * @param string $table
         *
         * @return bool
         */
        public function insert(array $values,array $toIgnore = [],string $table  =''): bool;

        /**
         * set tables to ignore
         *
         * @param array $tables
         *
         * @return Table
         */
        public function ignore(array $tables): Table;



        /**
         * show all tables in a database
         *
         * @return array
         */
        public function show(): array;

        /**
         * count all table in current database
         *
         * @return int
         */
        public function countTable(): int;

        /**
         * count number of records in a or multiples tables
         *
         * @param string|null $table
         * @param int         $mode
         *
         * @return array|int
         */
        public function count(string $table = '', int $mode = Eloquent::MODE_ONE_TABLE);


        /**
         * get all record in a table
         *
         * @param string $orderBy
         *
         * @return array
         */
        public function getRecords(string $orderBy = ''): array;

        /**
         * optimize a table
         *
         * @return bool
         */
        public function optimize(): bool;

        /**
         * modify an existing column
         *
         * @param string $column
         * @param string $type
         * @param int    $size
         *
         * @return bool
         */
        public function modifyColumn(string $column,string $type,int $size = 0): bool;


        /**
         * appends multiple columns in a table
         *
         * @param string $table
         * @param Table $instance
         * @param array $new_columns_names
         * @param array $new_columns_types
         * @param array $new_columns_length
         * @param array $new_column_order
         * @param array $existing_columns_selected
         * @param array $unique
         * @param array $null
         *
         * @return bool
         */
        public function append_columns(string $table, Table $instance, array  $new_columns_names, array $new_columns_types, array $new_columns_length, array $new_column_order, array $existing_columns_selected, array $unique, array $null): bool;

    }

}