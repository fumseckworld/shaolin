<?php

namespace Imperium {

    use Dotenv\Dotenv;
    use Exception;
    use GuzzleHttp\Psr7\ServerRequest;
    use Imperium\Bases\Base;
    use Imperium\Collection\Collection;
    use Imperium\Config\Config;
    use Imperium\Connexion\Connect;
    use Imperium\Dump\Dump;
    use Imperium\File\File;
    use Imperium\Flash\Flash;
    use Imperium\Html\Form\Form;
    use Imperium\Json\Json;
    use Imperium\Middleware\Middleware;
    use Imperium\Model\Model;
    use Imperium\Query\Query;
    use Imperium\Router\Router;
    use Imperium\Security\Auth\Oauth;
    use Imperium\Session\Session;
    use Imperium\Tables\Table;
    use Imperium\Users\Users;
    use Symfony\Component\HttpFoundation\Request;


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
    class App extends Zen implements Management
    {
        /**
         * @var Collection
         */
        private static $middleware;

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
         * @var array
         */
        private $hidden_tables;
        /**
         * @var array
         */
        private $hidden_bases;


        /**
         * @var Table
         */
        private $table;
        /**
         * @var Dotenv
         */
        private $env;


        /**
         *
         * Display all tables
         *
         *
         * @return array
         *
         * @throws Exception
         */
        public function show_tables(): array
        {
            return $this->table()->show();
        }

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
        public function show_users(): array
        {
           return $this->users()->show();
        }

        /**
         *
         * Display all bases
         *
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show_databases(): array
        {
            return $this->bases()->show();
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
         * @param string $table
         * @param string $column
         * @param string $order
         * @return array
         *
         * @throws Exception
         */
        public function all(string $table,string $column,string $order = DESC) : array
        {
            return $this->model()->from($table)->all($column,$order);
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
             return $this->table()->exist($table);
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
           return $this->table()->from($table)->set_collation($new_collation)->change_collation();
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
            return $this->table()->from($table)->set_charset($new_charset)->change_charset();
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
            return $this->table()->drop($table);
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
            return $this->table()->truncate($table);
        }

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
         * @throws Exception
         */
        public function append_column(string $table,string $column, string $type, int $size, bool $unique,bool $nullable): bool
        {
            return $this->table()->from($table)->append_column($column,$type,$size,$unique,$nullable);
        }

        /**
         *
         * Display all columns in a table
         *
         * @param string $table
         * @return array
         *
         * @throws Exception
         */
        public function show_columns(string $table): array
        {
            return $this->table()->from($table)->columns();
        }

        /**
         *
         * Display all column types
         *
         * @param string $table
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show_columns_types(string $table): array
        {
             return $this->table()->from($table)->columns_types();
        }

        /**
         *
         * Check if a column exist in a table
         *
         * @param string $table
         * @param string $column
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has_column(string $table,string $column): bool
        {
            return $this->table()->from($table)->has_column($column);
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
           return $this->table()->has();
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
         * @param string $table
         * @param int $id
         *
         * @return array
         *
         * @throws Exception
         */
        public function find(string $table,int $id): array
        {
           return $this->model()->from($table)->find($id);
        }

        /**
         *
         * Find a record or fail if not found
         *
         * @param string $table
         * @param int $id
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function find_or_fail(string $table,int $id): array
        {
           return $this->model()->from($table)->find_or_fail($id);
        }

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
         * @throws Exception
         */
        public function save(string $table,array $data, array $ignore = []): bool
        {
            return $this->table()->from($table)->save($data,$ignore);
        }

        /**
         *
         * Save the data in a table
         *
         * @param string $table
         * @param int $id
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function remove_record(string $table,int $id): bool
        {
            return $this->table()->from($table)->remove($id);
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
           return $this->table()->from($table)->update($id,$data,$ignore);
        }

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
         * @throws Exception
         */
        public function rename_column(string $table,string $column, string $new_name): bool
        {
            return $this->table()->from($table)->rename_column($column,$new_name);
        }

        /**
         *
         * Remove a column in current table
         *
         * @param string $table
         * @param string $column
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function remove_column(string $table,string $column): bool
        {
            return $this->table()->from($table)->remove_column($column);
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
           return $this->table()->from($table)->append_columns($$new_columns_names,$new_columns_types,$new_column_order,$existing_columns_selected,$unique,$null);
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
            return (new Dump($base,$tables))->dump();
        }

        /**
         *
         * @method __construct
         *
         * @throws Exception
         */
        public function __construct()
        {

            $file = 'db';
            $this->hidden_tables    = config($file,'hidden_tables');
            $this->hidden_bases     = config($file,'hidden_bases');

            $this->connect          = connect(config($file,'driver'),config($file,'base'),config($file,'username'),config($file,'password'),config($file,'host'),config($file,'dump'));
            $this->driver           = $this->connect->driver();

            $this->table            = new Table($this->connect);
            $this->query            = new Query($this->table,$this->connect);
            $this->base             = new Base($this->connect,$this->table);
            $this->users            = new Users($this->connect);
            $this->model            = new Model($this->connect,$this->table);
            $this->json             = new Json('app.json');
            $this->form             = new Form();
            self::$middleware        = collection();
            if (equal(request()->getScriptName(),'./vendor/bin/phpunit'))
               $path = dirname(request()->server->get('SCRIPT_FILENAME'),3);
            else
                $path = dirname(request()->server->get('DOCUMENT_ROOT'));

            $this->env              = Dotenv::create($path);
            $this->env->load();
        }



        /**
         *
         * Run the application
         *
         *
         * @return void
         *
         * @throws Exception
         *
         */
        public static function run(): void
        {
            if (config('app','debug'))
            {
                whoops();
            }
            echo (new Router(ServerRequest::fromGlobals()))->run();
        }

        /**
         *
         * @return Dotenv
         *
         */
        public function env()
        {
            return $this->env;
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
                case MYSQL :
                    return $this->bases()->rename($base,$new_name);
                break;
                case POSTGRESQL :
                    return $this->connect->execute("ALTER DATABASE $base RENAME TO $new_name");
                break;
                case SQLITE :
                    return File::rename($base,$new_name);
                break;
                default :
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
            return $this->table()->from($table)->rename($new_name);
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
        public function table(): Table
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

        /**
         *
         * Get the router
         *
         * @param ServerRequest $serverRequest
         *
         * @return Router
         *
         * @throws Exception
         *
         */
        public function router(ServerRequest $serverRequest): Router
        {
            return new Router($serverRequest);
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
            return collection($data);
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

        /**
         * @return Flash
         */
        public function flash(): Flash
        {
            return new Flash();
        }

        /**
         * @return Oauth
         */
        public function auth(): Oauth
        {
            return new Oauth($this->session());
        }

        /**
         * @return Session
         */
        public function session(): Session
        {
            return new Session();
        }

        public function request(): Request
        {
            return Request::createFromGlobals();
        }

        /**
         * @return Config
         *
         * @throws Exception
         *
         */
        public function config():Config
        {
            return Config::init();
        }

        /**
         *
         * @param Middleware $middleware
         *
         * @return App
         */
        /**
         *
         * @param string $middleware
         *
         * @return App
         */
        public function middleware(string $middleware): App
        {
            self::$middleware->add(new $middleware());

            return $this;
        }

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
        public function view(string $name,array $args = []): string
        {
            return view($name,$args);
        }
    }
}
