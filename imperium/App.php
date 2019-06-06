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
    use Imperium\Routing\Route;
    use Imperium\Validator\Validator;
    use Imperium\Versioning\Git\Git;
    use Imperium\Writing\Write;
    use Imperium\File\File;
    use Imperium\Flash\Flash;
    use Imperium\Html\Form\Form;
    use Imperium\Json\Json;
    use Imperium\Model\Model;
    use Imperium\Query\Query;
    use Imperium\Routing\Router;
    use Imperium\Security\Auth\Oauth;
    use Imperium\Session\ArraySession;
    use Imperium\Session\Session;
    use Imperium\Session\SessionInterface;
    use Imperium\Tables\Table;
    use Imperium\Users\Users;
    use Psr\Http\Message\ServerRequestInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;


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

        use Route;
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
         * @var Table
         */
        private $table;
        /**
         * @var Dotenv
         */
        private $env;
        /**
         * @var string
         */
        private $username;
        /**
         * @var string
         */
        private $password;

        /**
         * @var bool
         */
        private $debug;


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
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function all(string $table,string $column,string $order = DESC): array
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
         */
        public function table_exist(string $table): bool
        {
             return $this->table()->exist($table);
        }

        /**
         *
         * Check if a table not exist
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function  table_not_exist(string $table): bool
        {
            return $this->table()->not_exist($table);
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
         * @param bool $nullable
         *
         * @return bool
         *
         * @throws Exception
         */
        public function append_column(string $table,string $column, string $type, int $size, bool $nullable): bool
        {
            return $this->table()->column()->for($table)->add($column,$type,$size,$nullable);
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
            return $this->table()->column()->for($table)->show();
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
             return $this->table()->column()->for($table)->types();
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
            return $this->table()->column()->for($table)->exist($column);
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
         * @return object
         *
         * @throws Exception
         */
        public function find(string $table,int $id)
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
         * @return object
         *
         * @throws Exception
         *
         */
        public function find_or_fail(string $table,int $id)
        {
           return $this->model()->from($table)->find_or_fail($id);
        }

        /**
         *
         * Save the data in a table
         *
         * @param string $table
         * @param array $data
         *
         * @return bool
         *
         * @throws Exception
         */
        public function save(string $table,array $data): bool
        {
            return $this->table()->from($table)->save($data);
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
            return $this->table()->column()->for($table)->rename($column,$new_name);
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
            return $this->table()->column()->for($table)->drop($column);
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
            $this->driver           =  db(DB_DRIVER);
            $this->base             =  db(DB_NAME);
            $this->username         =  db(DB_USERNAME);
            $this->password         =  db(DB_PASSWORD);
            $this->hidden_tables    =  db(DB_HIDDEN_TABLES);
            $this->debug            =  db(DISPLAY_BUGS);


            if (equal($this->driver,SQLITE))
                $this->connect = connect(SQLITE,$this->base,'','','','dump');
            else
                $this->connect  = connect($this->driver,$this->base,$this->username,$this->password,LOCALHOST,'dump');


            $this->table            = new Table($this->connect);

            $this->query            = new Query($this->table,$this->connect);

            $this->base             = new Base($this->connect,$this->table);

            $this->users            = new Users($this->connect);

            $this->model            = new Model($this->connect,$this->table);

            $this->json             = new Json('app.json');

            $this->form             = new Form();

            if (equal(request()->getScriptName(),'./vendor/bin/phpunit'))
                $path = dirname(request()->server->get('SCRIPT_FILENAME'),3);
            else
                $path = dirname(request()->server->get('DOCUMENT_ROOT'));

            if (def($this->request()->server->get('PWD')))
                $path = $this->request()->server->get('PWD');

            is_true(File::not_exist("$path" .DIRECTORY_SEPARATOR .".env"),true,".env file was not found");

            $this->env              = Dotenv::create($path);

            $this->env->load();
        }


        /**
         *
         * Run the application
         *
         * @return Response
         *
         * @throws Exception
         *
         */
        public function run():Response
        {
            if ($this->debug)
                whoops();

            return $this->router(ServerRequest::fromGlobals())->run()->send();
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
         * @param ServerRequestInterface $serverRequest
         *
         * @return Router
         *
         * @throws Exception
         */
        public function router( ServerRequestInterface $serverRequest): Router
        {
            return new Router($serverRequest);
        }

        /**
         *
         * Management iof the array
         *
         * @param mixed $data
         *
         * @return Collection
         *
         */
        public function collection($data = []): Collection
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
         *
         * @return Oauth
         *
         * @throws Exception
         *
         */
        public function auth(): Oauth
        {
            return new Oauth($this->session());
        }

        /**
         * @return SessionInterface
         */
        public function session():SessionInterface
        {
            if ($this->request()->getScriptName() === './vendor/bin/phpunit')
                return new ArraySession();

            return new Session();
        }

        /**
         * @return Request
         */
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
         * @return Response
         */
        public function response(): Response
        {
            return new Response();
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
            return view(get_called_class(),$name,$args);
        }

        /**
         *
         * Redirect user to a route
         *
         * @param string $route
         * @param string $message
         * @param bool $success
         *
         * @return RedirectResponse
         *
         * @throws Exception
         *
         */
        public function redirect(string $route, string $message ='', bool $success = true): RedirectResponse
        {
            return redirect($route,$message,$success);
        }

        /**
         *
         * Redirect user back
         *
         * @param string $message
         * @param bool $success
         *
         * @return RedirectResponse
         *
         */
        public function back(string $message ='', bool $success = true): RedirectResponse
        {
           return back($message,$success);
        }

        /**
         *
         * Redirect user to an url
         *
         * @param string $url
         * @param string $message
         * @param bool $success
         *
         * @return RedirectResponse
         *
         */
        public function to(string $url, string $message='', bool $success = true): RedirectResponse
        {
            return to($url,$message,$success);
        }

        /**
         *
         * @param string $subject
         * @param string $message
         * @param string $author_email
         * @param string $to
         *
         * @return Write
         *
         * @throws Exception
         *
         */
        public function write(string $subject, string $message, string $author_email, string $to): Write
        {
            return new Write($subject,$message,$author_email,$to);
        }

        /**
         *
         * Management of git
         *
         * @param string $repository_path
         *
         * @return Git
         *
         * @throws Exception
         *
         */
        public function git(string $repository_path): Git
        {
           return new Git($repository_path);
        }

        /**
         *
         * Check the request
         *
         * @return RedirectResponse|Validator
         *
         */
        public function validator()
        {
           return new Validator($this->request());
        }

        /**
         * @return Model
         * @throws Exception
         */
        public function route(): Model
        {
           return $this->routes();
        }


        /**
         *
         * Generate url string
         *
         * @param string $route
         *
         * @param array $args
         * @return string
         *
         * @throws Exception
         */
        public function url(string $route,...$args): string
        {
            return Router::url($route,$args);
        }
    }
}
