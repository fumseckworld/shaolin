<?php

namespace Imperium {

    use Carbon\Carbon;
    use Dotenv\Dotenv;
    use GuzzleHttp\Psr7\ServerRequest;
    use Imperium\Bases\Base;
    use Imperium\Cache\Cache;
    use Imperium\Collection\Collection;
    use Imperium\Config\Config;
    use Imperium\Connexion\Connect;
    use Imperium\Debug\Bar;
    use Imperium\Dump\Dump;
    use Imperium\Exception\Kedavra;
    use Imperium\File\Download;
    use Imperium\Routing\Route;
    use Imperium\Routing\RouteResult;
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
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;


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
         * @var App
         */
        private static $instance;
        
        /**
         * @var Dotenv
         */
        private static $env;

        /**
         * @var string
         */
        private static $debug;

        /**
         * @var Cache
         */
        private static $cache;

        /**
         * @var Connect
         */
        private static $connect;

        /**
         * @var Table
         */
        private static $table;
        /**
         * @var Query
         */
        private static $query;
        /**
         * @var Base
         */
        private static $base;
        /**
         * @var Users
         */
        private static $users;
        /**
         * @var Model
         */
        private static $model;
        /**
         * @var Form
         */
        private static $form;

        private static $start_request_time;

        /**
         * @var string
         */
        private static $mode;


        use Route;


        /**
         *
         * Current driver
         *
         * @var string
         */
        private $driver;


        /**
         * @var string
         */
        private $username;
        /**
         * @var string
         */
        private $password;

        /**
         *
         * Display all tables
         *
         *
         * @return array
         *
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @return array
         *
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        public function all(string $table,string $column= '',string $order = DESC): array
        {
            return def($column) ? $this->model()->from($table)->all($column,$order) : $this->model()->from($table)->all($this->model()->from($table)->primary(),$order);
        }

        /**
         *
         * Check if a table exist
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        public function dump(bool $base,string ...$tables): bool
        {
            return (new Dump($base,$tables))->dump();
        }

        /**
         *
         * Get current lang
         *
         * @return mixed
         *
         * @throws Kedavra
         *
         */
        public function lang()
        {
            return config('locales','locale');
        }


        /**
         * Empêche la copie externe de l'instance.
         */
        private function __clone () {}

        /**
         * App constructor
         * .
         * @throws Kedavra
         *
         */
        private static function get (): App
        {
            $driver           =  db(DB_DRIVER);
            $base             =  db(DB_NAME);
            $username         =  db(DB_USERNAME);
            $password         =  db(DB_PASSWORD);

            self::$debug      =  server(DISPLAY_BUGS,false);

            self::$mode       =  server(ENV,'production');

            self::$cache = new Cache();

            self::$connect   = connect($driver,$base,$username,$password,LOCALHOST,'dump');

            self::$table            = new Table(self::$connect);

            self::$query            = new Query(self::$table,self::$connect);

            self::$base             = new Base(self::$connect,self::$table);

            self::$users            = new Users(self::$connect);

            self::$model            = new Model(self::$connect,self::$table);

            self::$form             = new Form();

            $file = ROOT . DIRECTORY_SEPARATOR;

            self::$env             = Dotenv::create($file,'.env');

            self::$env->load();

            return new static();
        }

        /**
         *
         * @return App
         *
         *
         * @throws Kedavra
         *
         */
        public static function instance(): App
        {
            if (is_null(self::$instance))
            {
                self::$start_request_time = now();
                self::$instance = self::get();
            }
            return self::$instance;
        }


        /**
         *
         * Run the application
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function run():Response
        {
            if ($this->debug())
                whoops();

           return  $this->router(ServerRequest::fromGlobals())->search()->call()->send();

        }

        /**
         *
         * @return RouteResult
         *
         * @throws Kedavra
         *
         */
        public function route_result(): RouteResult
        {
            return $this->router(ServerRequest::fromGlobals())->search();
        }

        /**
         *
         * Display request time
         *
         * @return int
         *
         */
        public function request_time(): int
        {
            return Carbon::now()->diffInRealMilliseconds(self::$start_request_time);
        }

        /**
         *
         * @return bool
         *
         */
        public function debug(): bool
        {
            return self::$debug;
        }

        /**
         *
         * @return Dotenv
         *
         */
        public function env(): Dotenv
        {
            return self::$env;
        }

        /**
         *
         * Management of json
         *
         * @param string $filename
         * @param string $mode
         *
         * @return Json
         *
         * @throws Kedavra
         *
         */
        public function json(string $filename,string $mode = EMPTY_AND_WRITE_FILE_MODE): Json
        {
            return new Json($filename,$mode);
        }

        /**
         *
         * Generate a json with all database, all users, and all tables
         *
         * @param string $filename
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function bases_users_tables_to_json(string $filename = 'all.json'): bool
        {
            return $this->json($filename)->add($this->show_databases(),'bases')->add($this->show_users(),'users')->add($this->show_tables(),'tables')->generate();
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
         * @throws Kedavra
         *
         */
        public function create_json(string $filename,array $data): bool
        {
            return $this->json($filename)->create($data);
        }

        /**
         *
         * Generate json with a query
         *
         * @param string $filename
         * @param string[] $queries
         * @return bool
         *
         * @throws Kedavra
         */
        public function sql_to_json(string $filename,string ...$queries): bool
        {
            $json = $this->json($filename);

            foreach ($queries as $k =>$v)
                $json->add($json->sql($this->connect(),$v,$k));

            return $json->generate();
        }

        /**
         *
         * @param int $records
         *
         * @return bool
         *
         * @throws Kedavra
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
         * @throws Kedavra
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
                    return self::$connect->execute("ALTER DATABASE $base RENAME TO $new_name");
                break;
                case SQLITE :
                    return (new File($base))->rename($new_name);
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
         * @throws Kedavra
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
           return self::$model;
        }

        /**
         * @return Query
         */
        public function query(): Query
        {
           return self::$query;
        }

        /**
         *
         * @return Users
         */
        public function users(): Users
        {
            return self::$users;
        }

        /**
         * @return Base
         */
        public function bases(): Base
        {
            return self::$base;
        }

        /**
         * @return Table
         */
        public function table(): Table
        {
            return self::$table;
        }

        /**
         *
         * @return Connect
         */
        public function connect(): Connect
        {
           return self::$connect;
        }

        /**
         *
         * Get the router
         *
         * @param ServerRequestInterface $serverRequest
         *
         * @return Router
         *
         * @throws Kedavra
         *
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
            return $validate ? self::$form->validate() : self::$form;
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
         * @throws Kedavra
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
            if (request()->getScriptName() === './vendor/bin/phpunit')
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
         * @throws Kedavra
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
         * @return string
         *
         * @throws Kedavra
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function view(string $name,array $args = []): string
        {
            $this->session()->set('view',$name);
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
         * @throws Kedavra
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
         * @throws Kedavra
         *
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
         * @param string $repository
         * @param string $owner
         * @return Git
         *
         * @throws Kedavra
         */
        public function git(string $repository, string $owner): Git
        {
           return new Git($repository,$owner);
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
         *
         * @return Model
         *
         * @throws Kedavra
         *
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
         * @throws Kedavra
         */
        public function url(string $route,...$args): string
        {
            return route($route,$args);
        }

        /**
         *
         * Download a file
         *
         * @param string $filename
         *
         * @return Response
         *
         * @throws Kedavra
         *
         */
        public function download(string $filename): Response
        {
           return (new Download($filename))->download();
        }

        /**
         *
         * Get records
         *
         * @param string $table
         * @param string $column
         * @param string $expected
         * @param string $condition
         * @param string $order_by
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function display(string $table, string $column = '', string $expected = '', string $condition = DIFFERENT, string $order_by = DESC): string
        {


        }

        /**
         *
         * Get records
         *
         * @param string $table
         * @param string $column
         * @param string $expected
         * @param string $condition
         * @param string $order_by
         *
         * @return array
         *
         * @throws Kedavra
         *
         */
        public function records(string $table, string $column = '', string $expected = '', string $condition = DIFFERENT, string $order_by = DESC): array
        {
            return get_records($table,$column,$expected,$condition,$order_by);
        }

        /**
         *
         * Encode data to json
         *
         * @param array $data
         *
         * @return string
         *
         */
        public function encode(array $data): string
        {
           return json_encode($data,JSON_FORCE_OBJECT);
        }

        /**
         *
         *
         * @param array $data
         *
         * @return JsonResponse
         *
         */
        public function json_response(array $data): JsonResponse
        {
            return new JsonResponse($data);
        }

        /**
         *
         * Get cache instance
         *
         * @return Cache
         *
         */
        public function cache(): Cache
        {
            return self::$cache;
        }

        /**
         *
         * Check if mode is enabled in production
         *
         * @return bool
         *
         */
        public function production(): bool
        {
            return self::$mode === 'production';
        }

        /**
         *
         * Get the debug bar
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function debug_bar(): string
        {
            return (new Bar())->render($this);
        }

        /**
         *
         * File management
         *
         * @param string $filename
         * @param string $mode
         *
         * @return File
         *
         * @throws Kedavra
         *
         */
        public function file(string $filename, string $mode = READ_FILE_MODE): File
        {
            return new File($filename,$mode);
        }
    }
}
