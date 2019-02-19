<?php

namespace Imperium {

    use Exception;


    use GuzzleHttp\Psr7\ServerRequest;
    use Imperium\Bases\Base;
    use Imperium\Collection\Collection;
    use Imperium\Config\Config;
    use Imperium\Connexion\Connect;
    use Imperium\Flash\Flash;
    use Imperium\Html\Form\Form;
    use Imperium\Model\Model;
    use Imperium\Query\Query;
    use Imperium\Router\Router;
    use Imperium\Session\Session;
    use Imperium\Tables\Table;
    use Imperium\Users\Users;
    use Imperium\View\View;
    use Symfony\Component\HttpFoundation\Request;

    interface Management
    {
        /**
         *
         * Display all tables
         *
         *
         * @return array
         *
         * @throws Exception
         */
        public function show_tables() : array;

        /**
         *
         * Display all users
         *
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show_users() : array;

        /**
         *
         * Display all bases
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show_databases() : array;

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
         * @param string $table
         * @param string $column
         * @param string $order
         *
         * @return array
         */
        public function all(string $table,string $column,string $order = DESC) : array;

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
         * @param string $new_collation
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function change_base_collation(string $new_collation) : bool;

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
         * @param string $new_charset
         *
         * @return bool
         *
         */
        public function change_base_charset(string $new_charset) : bool;

        /**
         *
         * Management iof the array
         *
         * @param array $data
         *
         * @return Collection
         *
         */
        public function collection(array $data = []): Collection;



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
         * Append a new column in an existing table
         *
         * @param string $table
         * @param string $column
         * @param string $type
         * @param int $size
         * @param bool $unique
         * @param bool $nullable
         *
         * @return bool
         *
         */
        public function append_column(string $table,string $column, string $type, int $size, bool $unique,bool $nullable): bool;

        /**
         *
         * Display all columns in a table
         *
         * @param string $table
         * @return array
         *
         */
        public function show_columns(string $table) : array;

        /**
         *
         * Display all column types
         *
         * @param string $table
         * @return array
         *
         */
        public function show_columns_types(string $table) : array;


        /**
         *
         * Check if a column exist in a table
         *
         * @param string $table
         * @param string $column
         *
         * @return bool
         *
         */
        public function has_column(string $table,string $column) : bool;

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
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function add_user(string $name,string $password) : bool;

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
         * @param string $table
         * @param int $id
         *
         * @return array
         *
         */
        public function find(string $table,int $id) : array;

        /**
         *
         * Find a record or fail if not found
         *
         * @param string $table
         * @param int $id
         *
         * @return array
         *
         */
        public function find_or_fail(string $table,int $id) : array;


        /**
         *
         * Save the data in a table
         *
         * @param string $table
         * @param array $data
         * @param array $ignore
         *
         * @return bool
         *
         */
        public function save(string $table,array $data,array $ignore = []) : bool;

        /**
         *
         * Save the data in a table
         *
         * @param string $table
         * @param int $id
         *
         * @return bool
         *
         */
        public function remove_record(string $table,int $id) : bool;

        /**
         *
         * Update a record in a table
         *
         * @param int $id
         *
         * @param array $data
         * @param string $table
         * @param array $ignore
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function update_record(int $id,array $data,string $table,array $ignore) : bool;


        /**
         *
         * Rename a column in current table
         *
         * @param string $table
         * @param string $column
         * @param string $new_name
         *
         * @return bool
         *
         */
        public function rename_column(string $table,string $column,string $new_name) : bool;

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
         * @param string $table
         * @param string $column
         *
         * @return bool
         *
         */
        public function remove_column(string $table,string $column) : bool;

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
         * @return Form
         */
        public function form(): Form;

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
        public function table(): Table;

        /**
         *
         * @return Connect
         */
        public function connect(): Connect;

        /**
         * @return Flash
         */
        public function flash(): Flash;

        /**
         * @return Session
         */
        public function session(): Session;

        public function request(): Request;

        /**
         * @return Config
         */
        public function config(): Config;

        /**
         *
         * @param ServerRequest $serverRequest
         *
         * @return Router
         *
         * @throws Exception
         *
         */
        public function router(ServerRequest $serverRequest): Router;

        /**
         *
         * Return a view
         *
         * @param string $name
         * @param array $args
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function view(string $name,array $args = []): string;

        /**
         *
         * Dump a base or  tables
         *
         * @param bool $base
         * @param string[] $tables
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function dump(bool $base,string ...$tables) : bool;

        /**
         *
         * @method __construct
         */
        public function __construct();

        // GETTER

        // END GETTER
    }
}
