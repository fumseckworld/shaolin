<?php

namespace Imperium;

use Exception;
use Imperium\Bases\Base;
use Imperium\Connexion\Connect;
use Imperium\File\File;
use Imperium\Model\Model;
use Imperium\Query\Query;
use Imperium\Tables\Table;
use Imperium\Users\Users;

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
     * @var string
     */
    private $current_table;

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
        return $this->table->hidden($hidden)->show();
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
       return $this->users->hidden($hidden)->show();
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
        return $this->base->hidden($hidden)->show();
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
       return $this->base->charsets();
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
        return $this->base->collations();
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
    public function all(string $order = 'desc') : array
    {
        return $this->model->all($order);
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
         return $this->table->exist($table);
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
        return $this->base->exist($base);
    }

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
    public function change_base_collation(string $base, string $new_collation): bool
    {
       return $this->base->set_name($base)->set_collation($new_collation)->change_collation();
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
       return $this->table->set_current_table($table)->set_collation($new_collation)->change_collation();
    }

    /**
     *
     * Change base charset
     *
     * @param string $base
     * @param string $new_charset
     *
     * @return bool
     *
     * @throws Exception
     */
    public function change_base_charset(string $base, string $new_charset): bool
    {
        return $this->base->set_name($base)->set_charset($new_charset)->change_charset();
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
        return $this->table->set_current_table($table)->set_charset($new_charset)->change_charset();
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
        return $this->users->exist($user);
    }

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
    public function create_table(string $table): bool
    {
        return $this->table->set_current_table($table)->create();
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
        return $this->table->drop($table);
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
        return $this->table->truncate($table);
    }

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
    public function append_field(string $type, string $name, bool $primary = false, int $length = 0, bool $unique = false, bool $null = false): Imperium
    {
        $this->table->append_field($type,$name,$primary,$length,$unique,$null);
      return $this;
    }

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
    public function append_column(string $column, string $type, int $size, bool $unique): bool
    {
        return $this->table->append_column($column,$type,$size,$unique);
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
        return $this->table->get_columns();
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
         return $this->table->get_columns_types();
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
        return $this->table->has_column($column);
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
       return $this->table->has();
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
       return $this->base->has();
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
        return $this->users->has();
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
        return $this->base->set_collation($collation)->set_charset($charset)->create($name);
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
        return $this->base->drop($name);
    }

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
    public function add_user(string $name, string $password, string $rights = ''): bool
    {
        return $this->users->set_name($name)->set_password($password)->set_rights($rights)->create();
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
       return $this->users->update_password($name,$password);
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
       return $this->users->drop($name);
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
       return $this->model->find($id);
    }

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
    public function where(string $column, string $condition, $expected): array
    {
        return $this->model->where($column,$condition,$expected);
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
       return $this->model->findOrFail($id);
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
    public function save(array $data, array $ignore): bool
    {
        return $this->table->insert($data,$ignore);
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
        return $this->table->remove_by_id($id);
    }

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
    public function update_record(int $id, array $data, array $ignore): bool
    {
       return $this->table->update($id,$data,$ignore);
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
     *
     */
    public function rename_column(string $column, string $new_name): bool
    {
        return $this->table->set_current_table($this->current_table)->rename_column($column,$new_name);
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
        return $this->table->set_current_table($this->current_table)->remove_column($column);
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
       return $this->table->append_columns($table,$this->table,$new_columns_names,$new_columns_types,$new_column_order,$existing_columns_selected,$unique,$null);
    }

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
    public function dump(bool $base = true, string $table = ''): bool
    {
        return dumper($this->connect,$base,$table);
    }


    /**
     * Management constructor.
     *
     * @param Connect $connect
     * @param string $current_table
     * @throws Exception
     */
    public function __construct(Connect $connect,string $current_table)
    {
        $this->connect = $connect;
        $this->driver = $connect->get_driver();
        $this->table = new Table($connect);

        $this->query = new  Query($this->table,$connect);
        $this->base = new Base($connect);
        $this->users = new Users($connect);
        $this->model = new Model($connect,$this->table,$current_table);
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
                return false;
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
     * Set current table
     *
     * @param string $table
     *
     * @return Imperium
     */
    public function set_current_table(string $table): Imperium
    {
        $this->current_table = $table;

        return $this;
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
        return $this->table->set_current_table($table)->rename($new_name);
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
        return $this->table;
    }

    /**
     *
     * @return Connect
     */
    public function connect(): Connect
    {
       return $this->connect;
    }
}