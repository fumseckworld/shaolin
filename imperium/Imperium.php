<?php

namespace Imperium {

    use Exception;
    use Imperium\Bases\Base;
    use Imperium\Collection\Collection;
    use Imperium\Connexion\Connect;
    use Imperium\Dump\Dump;
    use Imperium\File\File;
    use Imperium\Html\Form\Form;
    use Imperium\Json\Json;
    use Imperium\Model\Model;
    use Imperium\Query\Query;
    use Imperium\Tables\Table;
    use Imperium\Users\Users;
    use Imperium\View\View;


    /**
    *
    * Management of the app
    *
    * @author Willy Micieli <micieli@laposte.net>
    *
    * @package imperium
    *
    * @version 4
    *
    * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE
    *
    **/
    class Imperium extends Zen implements Management
    {

        /**
         *
         * Connexion
         *
         * @var Connect
         *
         */
        private $connect;

        /**
         *
         * Current table
         *
         * @var Table
         */
        private $table;

        /**
         *
         * Current driver
         *
         * @var string
         */
        private $driver;

        /**
         * @var Base
         */
        private $base;

        /**
         * @var Users
         */
        private $users;

        /**
         * @var Query
         */
        private $query;

        /**
         * @var Model
         */
        private $model;

        /**
         * @var Json
         */
        private $json;


        /**
         * @var Form
         */
        private $form;

        /**
         * @var Table
         */
        private $tables;

        /**
         * @var View
         */
        private $view;


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
        public function show_tables(array $hidden = []): array
        {
            return $this->tables()->hidden($hidden)->show();
        }

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
        public function show_users(array $hidden = []): array
        {
           return $this->users()->hidden($hidden)->show();
        }

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
        public function show_databases(array $hidden = []): array
        {
            return $this->bases()->hidden($hidden)->show();
        }

        /**
         *
         * Display all charsets available
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function charsets(): array
        {
           return $this->bases()->charsets();
        }

        /**
         *
         * Display all collation available
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function collations(): array
        {
            return $this->bases()->collations();
        }


        /**
         *
         * Display all records inside a table
         *
         * @param string $order
         * @return array
         *
         * @throws Exception
         */
        public function all(string $order = Table::DESC) : array
        {
            return $this->model()->all($order);
        }

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
        public function table_exist(string $table): bool
        {
             return $this->tables()->exist($table);
        }

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
        public function base_exist(string $base): bool
        {
            return $this->bases()->exist($base);
        }

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
        public function change_base_collation(string $new_collation): bool
        {
           return $this->bases()->set_collation($new_collation)->change_collation();
        }

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
        public function change_table_collation(string $table, string $new_collation): bool
        {
           return $this->tables()->from($table)->set_collation($new_collation)->change_collation();
        }

        /**
         *
         * Change base charset
         *
         * @param string $new_charset
         *
         * @return bool
         *
         * @throws Exception
         */
        public function change_base_charset(string $new_charset): bool
        {
            return $this->bases()->set_charset($new_charset)->change_charset();
        }

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
        public function change_table_charset(string $table, string $new_charset): bool
        {
            return $this->tables()->from($table)->set_charset($new_charset)->change_charset();
        }

        /**
         *
         * Check if a user exist
         *
         * @param string $user
         * @return bool
         *
         * @throws Exception
         */
        public function user_exist(string $user): bool
        {
            return $this->users()->exist($user);
        }

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
        public function remove_table(string $table): bool
        {
            return $this->tables()->drop($table);
        }

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
        public function empty_table(string $table): bool
        {
            return $this->tables()->truncate($table);
        }

        /**
         *
         * Append a new column in an existing table
         *
         * @param string $column
         * @param string $type
         * @param int $size
         * @param bool $unique
         * @param bool $nullable
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function append_column(string $column, string $type, int $size, bool $unique,bool $nullable): bool
        {
            return $this->tables()->append_column($column,$type,$size,$unique,$nullable);
        }

        /**
         *
         * Display all columns in a table
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show_columns(): array
        {
            return $this->tables()->columns();
        }

        /**
         *
         * Display all column types
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show_columns_types(): array
        {
             return $this->tables()->columns_types();
        }

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
        public function has_column(string $column): bool
        {
            return $this->tables()->has_column($column);
        }

        /**
         *
         * Check if current base has table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has_tables(): bool
        {
           return $this->tables()->has();
        }

        /**
         *
         * Check if server has bases
         *
         * @return bool
         *
         * @throws Exception
         */
        public function has_bases(): bool
        {
           return $this->bases()->has();
        }

        /**
         *
         * Check if server has users
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has_users(): bool
        {
            return $this->users()->has();
        }

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
        public function add_database(string $name, string $charset = '', string $collation = ''): bool
        {
            return def($charset,$collation) ? $this->bases()->set_collation($collation)->set_charset($charset)->create($name) : $this->bases()->create($name);
        }

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
        public function remove_database(string $name): bool
        {
            return $this->bases()->drop($name);
        }

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
        public function add_user(string $name, string $password): bool
        {
            return $this->users()->set_name($name)->set_password($password)->create();
        }

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
        public function change_user_password(string $name, string $password): bool
        {
           return $this->users()->update_password($name,$password);
        }

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
        public function remove_user(string $name): bool
        {
           return $this->users()->drop($name);
        }

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
        public function find(int $id): array
        {
           return $this->model()->find($id);
        }

        /**
         *
         * Call the model and return the result of the where clause
         *
         * @method where
         *
         * @param  string $column The column name
         * @param  string $condition The condition
         * @param  mixed $expected The expected value
         *
         * @return array
         *
         * @throws Exception
         * 
         */
        public function where(string $column, string $condition, $expected): array
        {
            return $this->model()->where($column,$condition,$expected)->get();
        }

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
        public function find_or_fail(int $id): array
        {
           return $this->model()->find_or_fail($id);
        }

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
        public function save(array $data, array $ignore = []): bool
        {
            return $this->tables()->save($data,$this->tables()->current(),$ignore);
        }

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
        public function remove_record(int $id): bool
        {
            return $this->tables()->remove($id);
        }

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
         */
        public function update_record(int $id, array $data, string $table,array $ignore = []): bool
        {
           return $this->tables()->update($id,$data,$table,$ignore);
        }

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
         */
        public function rename_column(string $column, string $new_name): bool
        {
            return $this->tables()->rename_column($column,$new_name);
        }

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
        public function remove_column(string $column): bool
        {
            return $this->tables()->remove_column($column);
        }


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
        public function append_columns(string $table, array $new_columns_names, array $new_columns_types, array $new_columns_length, array $new_column_order, array $existing_columns_selected, array $unique, array $null): bool
        {
           return $this->tables()->append_columns($table,$this->table,$new_columns_names,$new_columns_types,$new_column_order,$existing_columns_selected,$unique,$null);
        }

        /**
         *
         * Dump a base or multiples tables
         *
         * @method dump
         *
         * @param  bool $base
         * @param  string[] $tables
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function dump(bool $base,string ...$tables): bool
        {
            return (new Dump($this->connect,$base,$tables))->dump();
        }

        /**
         *
         * @method __construct
         *
         * @param  Connect $connect The connection to the base
         * @param  string $current_table The current table
         * @param string $views_dir
         * @param  array $hidden_tables All hidden tables in current base
         * @param  array $hidden_bases All hidden bases for the drivers
         *
         * @throws Exception
         *
         */
        public function __construct(Connect $connect,string $current_table,string $views_dir,array $hidden_tables, array $hidden_bases)
        {
            $this->connect   = $connect;
            $this->driver    = $connect->driver();
            $this->tables    = new Table($connect,$current_table,$hidden_tables);
            $this->table     = $current_table;
            $this->query     = new Query($this->tables,$connect);
            $this->base      = new Base($connect,$this->tables,$hidden_tables,$hidden_bases);
            $this->users     = new Users($connect);
            $this->model     = new Model($connect,$this->tables,$current_table);
            $this->json      = new Json();
            $this->form      = new Form();
            $this->view      = new View($views_dir);
        }


        /**
         *
         * Management of json
         *
         * @return Json
         *
         */
        public function json(): Json
        {
            return $this->json;
        }

        /**
         *
         * Generate a json with all database, all users, and all tables
         *
         * @param string $filename
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function bases_users_tables_to_json(string $filename): bool
        {
            return $this->json()->set_name($filename)->add($this->show_databases(),'bases')->add($this->show_users(),'users')->add($this->show_tables(),'tables')->generate();
        }

        /**
         *
         * Create a json
         *
         * @param string $filename
         * @param array $data
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function create_json(string $filename,array $data): bool
        {
            return $this->set_json_name($filename)->create($data);
        }

        /**
         *
         * Generate json with a query
         *
         * @param string $filename
         * @param string $query
         * @param string $key
         *
         * @return bool
         *
         * @throws Exception
         */
        public function sql_to_json(string $filename,string $query,string $key = ''): bool
        {
            return $this->set_json_name($filename)->sql($this->connect(),$query,$key)->generate();
        }

        /**
         *
         * Define the name of the json file
         *
         * @param string $name
         *
         * @return Json
         */
        public function set_json_name(string $name): Json
        {
            return $this->json()->set_name($name);
        }


        /**
         *
         * Decode a json file or a json string
         *
         * @param string $data
         * @param bool $assoc
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function json_decode(string $data,bool $assoc = false)
        {
            return $this->json()->set_name($data)->decode($assoc);

        }

        /**
         *
         * @param int $records
         *
         * @return bool
         *
         * @throws Exception
         */
        public function seed_database(int $records = 100): bool
        {
            return $this->bases()->seed($records);
        }
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
        public function rename_base(string $base, string $new_name): bool
        {
            switch ($this->driver)
            {
                case Connect::MYSQL:
                    return $this->bases()->rename($base,$new_name);
                break;
                case Connect::POSTGRESQL:
                    return $this->connect->execute("ALTER DATABASE $base RENAME TO $new_name");
                break;
                case Connect::SQLITE:
                    return File::rename($base,$new_name);
                break;
                default:
                    return false;
                break;
            }
        }

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
        public function rename_table(string $table, string $new_name): bool
        {
            return $this->tables()->from($table)->rename($new_name);
        }


        /**
         *
         * @return Model
         *
         */
        public function model(): Model
        {
           return $this->model;
        }

        /**
         * @return Query
         */
        public function query(): Query
        {
           return $this->query;
        }

        /**
         *
         * @return Users
         */
        public function users(): Users
        {
            return $this->users;
        }

        /**
         * @return Base
         */
        public function bases(): Base
        {
            return $this->base;
        }

        /**
         * @return Table
         */
        public function tables(): Table
        {
            return $this->tables;
        }

        /**
         *
         * @return Connect
         */
        public function connect(): Connect
        {
           return $this->connect;
        }

        /**
         *
         * @return View
         *
         */
        public function view(): View
        {
            return $this->view;
        }

        /**
         *
         * Management iof the array
         *
         * @param array $data
         *
         * @return Collection
         *
         */
        public function collection(array $data = []): Collection
        {
            return new Collection($data);
        }

        /**
         *
         * @param bool $validate
         *
         * @return Form
         */
        public function form(bool $validate = false): Form
        {
            return $validate ? $this->form->validate() : $this->form;
        }
    }
}
