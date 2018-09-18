<?php

namespace Imperium {

    use Exception;


    use Imperium\Bases\Base;
    use Imperium\Connexion\Connect;
    use Imperium\Model\Model;
    use Imperium\Query\Query;
    use Imperium\Tables\Table;
    use Imperium\Users\Users;

    interface Management
    {
        /**
         *
         * Display all tables
         *
         * @param array $hidden
         *
         * @return array
         *
         * @throws Exception
         */
        public function show_tables(array $hidden = []) : array;

        /**
         *
         * Display all users
         *
         * @param array $hidden
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show_users(array $hidden = []) : array;

        /**
         *
         * Display all bases
         *
         * @param array $hidden
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show_databases(array $hidden = []) : array;

        /**
         *
         * Display all charsets available
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function charsets() : array;

        /**
         *
         * Display all collation available
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function collations() : array;


        // MODEL

        /**
         *
         * Display all records inside a table
         *
         * @param string $order
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function all(string $order = 'desc') : array;

        /**
         *
         * Check if a table exist
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function table_exist(string $table) : bool;

        /**
         *
         * Check if a base exist
         *
         * @param string $base
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function base_exist(string $base) : bool;

        /**
         *
         * Change base collation
         *
         * @param string $base
         * @param string $new_collation
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function change_base_collation(string $base, string $new_collation) : bool;

        /**
         *
         * Change table collation
         *
         * @param string $table
         * @param string $new_collation
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function change_table_collation(string $table, string $new_collation) : bool;

        /**
         *
         * Change base charset
         *
         * @param string $base
         * @param string $new_charset
         * @return bool
         *
         * @throws Exception
         *
         */
        public function change_base_charset(string $base, string $new_charset) : bool;


        /**
         *
         * Change table charset
         *
         * @param string $table
         * @param string $new_charset
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function change_table_charset(string $table, string $new_charset) : bool;

        /**
         *
         * Check if a user exist
         *
         * @param string $user
         *
         * @return bool
         *
         * @throws Exception
         */
        public function user_exist(string $user) : bool;

        /**
         *
         * Create a table
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function create_table(string $table) : bool;

        /**
         *
         * Remove a table
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function remove_table(string $table) : bool;

        /**
         *
         * Empty all records in a table
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function empty_table(string $table) : bool;



        /**
         *
         * Add new column in create table task
         *
         * @param string $type
         * @param string $name
         * @param bool $primary
         * @param int $length
         * @param bool $unique
         * @param bool $null
         *
         * @return Imperium
         *
         */
        public function append_field(string $type, string $name, bool $primary = false, int $length = 0, bool $unique = false, bool $null = false) : Imperium;

        /**
         *
         * Append a new column in an existing table
         *
         * @param string $column
         * @param string $type
         * @param int $size
         * @param bool $unique
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function append_column(string $column, string $type, int $size, bool $unique): bool;
        /**
         *
         * Display all columns in a table
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show_columns() : array;

        /**
         *
         * Display all column types
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show_columns_types() : array;


        /**
         *
         * Check if a column exist in a table
         *
         * @param string $column
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has_column(string $column) : bool;

        /**
         *
         * Check if current base has table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has_tables() : bool;

        /**
         *
         * Check if server has bases
         *
         * @return bool
         *
         * @throws Exception
         */
        public function has_bases() : bool;

        /**
         *
         * Check if server has users
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has_users() : bool;

        /**
         *
         * Create a new database
         *
         * @param string $name
         * @param string $charset
         *
         * @param string $collation
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function add_database(string $name,string $charset = '',string $collation = '') : bool;

        /**
         *
         * Remove a database
         *
         * @param string $name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function remove_database(string $name) : bool;

        /**
         *
         * Create a new user
         *
         * @param string $name
         * @param string $password
         * @param string $rights
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function add_user(string $name,string $password,string $rights = '') : bool;

        /**
         *
         * Change user password
         *
         * @param string $name
         * @param string $password
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function change_user_password(string $name,string $password) : bool;

        /**
         *
         * Remove an user
         *
         * @param string $name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function remove_user(string $name) : bool;

        /**
         *
         * Find a record by id
         *
         * @param int $id
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function find(int $id) : array;

        /**
         *
         * Find a record by a where clause
         *
         * @param string $column
         * @param string $condition
         * @param $expected
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function where(string $column,string $condition,$expected) : array;


        /**
         *
         * Find a record or fail if not found
         *
         * @param int $id
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function find_or_fail(int $id) : array;


        /**
         *
         * Save the data in a table
         *
         * @param array $data
         * @param array $ignore
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function save(array $data,array $ignore) : bool;

        /**
         *
         * Save the data in a table
         *
         * @param int $id
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function remove_record(int $id) : bool;

        /**
         *
         * Update a record in a table
         *
         * @param int $id
         *
         * @param array $data
         * @param array $ignore
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function update_record(int $id,array $data,array $ignore) : bool;


        /**
         *
         * Rename a column in current table
         *
         * @param string $column
         * @param string $new_name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function rename_column(string $column,string $new_name) : bool;

        /**
         *
         * Rename a table
         *
         * @param string $table
         * @param string $new_name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function rename_table(string $table,string $new_name) : bool;

        /**
         *
         * Rename a base
         *
         * @param string $base
         * @param string $new_name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function rename_base(string $base,string $new_name) : bool;


        /**
         *
         * Remove a column in current table
         *
         * @param string $column
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function remove_column(string $column) : bool;

        /**
         *
         * Append multiples columns inb current table
         *
         * @param string $table
         * @param array $new_columns_names
         * @param array $new_columns_types
         * @param array $new_columns_length
         * @param array $new_column_order
         * @param array $existing_columns_selected
         * @param array $unique
         * @param array $null
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function append_columns(string $table,array  $new_columns_names, array $new_columns_types, array $new_columns_length, array $new_column_order, array $existing_columns_selected, array $unique, array $null) : bool;

        /**
         *
         * @return Model
         *
         */
        public function model(): Model;

        /**
         * @return Query
         */
        public function query(): Query;

        /**
         *
         * @return Users
         */
        public function users(): Users;

        /**
         * @return Base
         */
        public function bases(): Base;

        /**
         * @return Table
         */
        public function tables(): Table;

        /**
         *
         * @return Connect
         */
        public function connect(): Connect;

        /**
         *
         * Dump a base or a table
         *
         * @param bool $base
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function dump(bool $base = true,string $table = '') : bool;

        /**
         * Management constructor.
         *
         * @param Connect $connect
         * @param string $current_table
         */
        public function __construct(Connect $connect,string $current_table);

        // GETTER

        // END GETTER
    }
}