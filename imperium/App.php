<?php

	namespace Imperium
	{

		use DI\DependencyException;
		use DI\NotFoundException;
		use Dotenv\Dotenv;
		use GuzzleHttp\Psr7\ServerRequest;
		use Imperium\Asset\Asset;
		use Imperium\Bases\Base;
		use Imperium\Cache\Cache;
		use Imperium\Collection\Collect;
		use Imperium\Config\Config;
		use Imperium\Connexion\Connect;
		use Imperium\Debug\Bar;
		use Imperium\Dump\Dump;
		use Imperium\Exception\Kedavra;
		use Imperium\File\Download;
		use Imperium\Request\Request;
		use Imperium\Routing\Route;
		use Imperium\Routing\RouteResult;
		use Imperium\Session\Session;
		use Imperium\Validator\Validator;
		use Imperium\Versioning\Git\Git;
		use Imperium\View\View;
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
		use Imperium\Session\SessionInterface;
		use Imperium\Tables\Table;
		use Imperium\Users\Users;
		use Psr\Http\Message\ServerRequestInterface;
		use Symfony\Component\HttpFoundation\JsonResponse;
		use Symfony\Component\HttpFoundation\RedirectResponse;
		use Symfony\Component\HttpFoundation\Response;
		use Twig\Error\LoaderError;
		use Twig\Error\RuntimeError;
		use Twig\Error\SyntaxError;
    	

/**
		 * Class App
		 *
		 * @author Willy Micieli
		 *
		 * @package Imperium
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class App extends Zen implements Management
		{

			/**
			 * @var Connect
			 */
			private $connect;

			/**
			 * @var Table
			 */
			private $table;

			/**
			 * @var Query
			 */
			private $query;

			/**
			 * @var Base
			 */
			private $base;

			/**
			 * @var Users
			 */
			private $users;

			/**
			 * @var Model
			 */
			private $model;
			/**
			 * @var Form
			 */
			private $form;

			/**
			 * @var bool
			 */
			private $debug;

			/**
			 * @var Dotenv
			 */
			private $env;

			/**
			 *
			 * @var Cache
			 *
			 */
			private $cache;

			/**
			 * @var
			 */
			private $start_request_time;


			/**
			 * @var Request
			 */
			private $request;

			/**
			 *
			 * @var Oauth
			 *
			 */
			private $oauth;

			/**
			 *
			 * @Inject
			 *
			 * @var View
			 *
			 */
			private $view;

			/**
			 * @var SessionInterface
			 */
			private $session;

			/**
			 * @var Flash
			 */
			private $flash;

			/**
			 * @var RouteResult
			 */
			private $result;


			/**
			 * App constructor.
			 *
			 * @throws DependencyException
			 * @throws NotFoundException
			 * @throws Kedavra
			 *
			 */
			public function __construct()
			{

				if ($this->debug())
					whoops();


				$this->start_request_time = now();
				$this->view = $this->app(View::class);
				$this->request = $this->app(Request::class);
				$this->cache = $this->app(Cache::class);
				$this->model = $this->app(Model::class);
				$this->table = $this->app(Table::class);
				$this->connect = $this->app(Connect::class);

				$this->query = $this->app(Query::class);

				$this->env = Dotenv::create(ROOT, '.env');

				$this->env->load();

				Route::manage()->create_route_table();
			}

			
			/**
			 *
			 * Display all tables
			 *
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
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
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return array
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
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return array
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
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return array
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
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return array
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
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function all(string $table, string $column = '', string $order = DESC): array
			{
				return def($column) ? $this->model()->from($table)->all($column, $order) : $this->model()->from($table)->all($this->model()->from($table)->primary(), $order);
			}

			/**
			 *
			 * Check if a table exist
			 *
			 * @param string $table
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
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
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function table_not_exist(string $table): bool
			{
				return $this->table()->not_exist($table);
			}

			/**
			 *
			 * Check if a base exist
			 *
			 * @param string $base
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
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
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
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
			 * @throws Kedavra
			 *
			 * @return bool
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
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
			 *
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
			 * @throws Kedavra
			 *
			 * @return bool
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
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
			 *
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
			 * @throws Kedavra
			 *
			 * @return bool
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
			 * @throws Kedavra
			 *
			 * @return bool
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
			 * @param int    $size
			 * @param bool   $nullable
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function append_column(string $table, string $column, string $type, int $size, bool $nullable): bool
			{
				return $this->table()->column()->for($table)->add($column, $type, $size, $nullable);
			}

			/**
			 *
			 * Display all columns in a table
			 *
			 * @param string $table
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
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
			 * @throws Kedavra
			 *
			 * @return array
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
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function has_column(string $table, string $column): bool
			{
				return $this->table()->column()->for($table)->exist($column);
			}

			/**
			 *
			 * Check if current base has table
			 *
			 * @throws Kedavra
			 *
			 * @return bool
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
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
			 *
			 */
			public function has_bases(): bool
			{
				return $this->bases()->has();
			}

			/**
			 *
			 * Check if server has users
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
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
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
			 *
			 */
			public function add_database(string $name, string $charset = '', string $collation = ''): bool
			{
				return def($charset, $collation) ? $this->bases()->set_collation($collation)->set_charset($charset)->create($name) : $this->bases()->create($name);
			}

			/**
			 *
			 * Remove a database
			 *
			 * @param string $name
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
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
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
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
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
			 *
			 */
			public function change_user_password(string $name, string $password): bool
			{
				return $this->users()->update_password($name, $password);
			}

			/**
			 *
			 * Remove an user
			 *
			 * @param string $name
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
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
			 * @param int    $id
			 *
			 * @throws Kedavra
			 *
			 * @return object
			 *
			 */
			public function find(string $table, int $id)
			{
				return $this->model()->from($table)->find($id);
			}

			/**
			 *
			 * Find a record or fail if not found
			 *
			 * @param string $table
			 * @param int    $id
			 *
			 * @throws Kedavra
			 * @return object
			 *
			 */
			public function find_or_fail(string $table, int $id)
			{
				return $this->model()->from($table)->find_or_fail($id);
			}

			/**
			 *
			 * Save the data in a table
			 *
			 * @param string $table
			 * @param array  $data
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function save(string $table, array $data): bool
			{
				return $this->table()->from($table)->save($this->model(), $data);
			}

			/**
			 *
			 * Save the data in a table
			 *
			 * @param string $table
			 * @param int    $id
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function remove_record(string $table, int $id): bool
			{
				return $this->table()->from($table)->remove($id);
			}

			/**
			 *
			 * Update a record in a table
			 *
			 * @param int    $id
			 *
			 * @param array  $data
			 * @param string $table
			 * @param array  $ignore
			 *
			 * @throws Kedavra
			 * @return bool
			 *
			 */
			public function update_record(int $id, array $data, string $table, array $ignore = []): bool
			{
				return $this->table()->from($table)->update($id, $data, $ignore);
			}

			/**
			 *
			 * Rename a column in current table
			 *
			 * @param string $table
			 * @param string $column
			 * @param string $new_name
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function rename_column(string $table, string $column, string $new_name): bool
			{
				return $this->table()->column()->for($table)->rename($column, $new_name);
			}

			/**
			 *
			 * Remove a column in current table
			 *
			 * @param string $table
			 * @param string $column
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function remove_column(string $table, string $column): bool
			{
				return $this->table()->column()->for($table)->drop($column);
			}

			/**
			 *
			 * Dump a base or multiples tables
			 *
			 * @method dump
			 *
			 * @param bool     $base
			 * @param string[] $tables
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function dump(bool $base, string ...$tables): bool
			{
				return (new Dump($base, $tables))->dump();
			}

			/**
			 *
			 * Get current lang
			 *
			 * @throws Kedavra
			 *
			 * @return mixed
			 *
			 */
			public function lang()
			{
				return config('locales', 'locale');
			}


			/**
			 *
			 * Run the application
			 *
			 * @throws Kedavra
			 * @return Response
			 *
			 */
			public function run(): Response
			{
				return $this->route_result()->call()->send();
			}

			/**
			 *
			 * @throws Kedavra
			 * @return RouteResult
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
				return now()->diffInRealMilliseconds($this->start_request_time);
			}

			/**
			 *
			 * @return bool
			 *
			 */
			public function debug(): bool
			{
				return server(DISPLAY_BUGS) === "true";
			}

			/**
			 *
			 * @return mixed
			 *
			 */
			public function env($key)
			{
				return getenv($key);
			}

			/**
			 *
			 * Management of json
			 *
			 * @param string $filename
			 * @param string $mode
			 *
			 * @throws Kedavra
			 *
			 * @return Json
			 *
			 */
			public function json(string $filename, string $mode = EMPTY_AND_WRITE_FILE_MODE): Json
			{
				return new Json($filename, $mode);
			}

			/**
			 *
			 * Generate a json with all database, all users, and all tables
			 *
			 * @param string $filename
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
			 *
			 */
			public function bases_users_tables_to_json(string $filename = 'all.json'): bool
			{
				return $this->json($filename)->add($this->show_databases(), 'bases')->add($this->show_users(), 'users')->add($this->show_tables(), 'tables')->generate();
			}

			/**
			 *
			 * Create a json
			 *
			 * @param string $filename
			 * @param array  $data
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function create_json(string $filename, array $data): bool
			{
				return $this->json($filename)->create($data);
			}

			/**
			 *
			 * Generate json with a query
			 *
			 * @param string   $filename
			 * @param string[] $queries
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function sql_to_json(string $filename, string ...$queries): bool
			{
				$json = $this->json($filename);

				foreach ($queries as $k => $v)
					$json->add($json->sql($this->connect(), $v, $k));

				return $json->generate();
			}

			/**
			 *
			 * @param int $records
			 *
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
			 *
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
			 * @throws DependencyException
			 * @throws Kedavra
			 * @throws NotFoundException
			 * @return bool
			 *
			 */
			public function rename_base(string $base, string $new_name): bool
			{
				switch ($this->connect->driver())
				{
				case MYSQL :
					return $this->bases()->rename($base, $new_name);
					break;
				case POSTGRESQL :
					return $this->connect->execute("ALTER DATABASE $base RENAME TO $new_name");
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
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function rename_table(string $table, string $new_name): bool
			{
				return $this->table()->from($table)->rename($new_name);
			}


			/**
			 *
			 * @return Model
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
			 * @throws DependencyException
			 * @throws NotFoundException
			 * @return Users
			 */
			public function users(): Users
			{
				return $this->app(Users::class);
			}

			/**
			 * @throws DependencyException
			 * @throws NotFoundException
			 * @return Base
			 */
			public function bases(): Base
			{
				return $this->app(Base::class);
			}

			/**
			 *
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
			 * @throws Kedavra
			 * @return Router
			 *
			 */
			public function router(ServerRequestInterface $serverRequest): Router
			{
				return new Router($serverRequest);
			}

			/**
			 *
			 * Management iof the array
			 *
			 * @param mixed $data
			 *
			 * @return Collect
			 *
			 */
			public function collection($data = []): Collect
			{
				return collect($data);
			}

			/**
			 *
			 * @param bool $validate
			 *
			 * @return Form
			 */
			public function form(bool $validate = false): Form
			{
				return $validate ? (new Form())->validate() : new Form();
			}

			/**
			 * @throws Kedavra
			 * @return Flash
			 */
			public function flash(): Flash
			{
				return new Flash($this->session());
			}

			/**
			 *
			 * @throws Kedavra
			 * @return Oauth
			 *
			 */
			public function auth(): Oauth
			{
				return new Oauth($this->session(), $this->model());
			}

			/**
			 *
			 * @throws Kedavra
			 * @return SessionInterface
			 *
			 */
			public function session(): SessionInterface
			{

				return def(strstr(request()->getScriptName(), 'phpunit')) ? new ArraySession() : new Session();

			}

			/**
			 * @return Request
			 */
			public function request(): Request
			{
				return $this->request;
			}

			/**
			 * @param string $filename
			 * @param        $key
			 *
			 * @throws Kedavra
			 *
			 * @return mixed
			 *
			 */
			public function config(string $filename, $key)
			{
				return (new Config($filename, $key))->value();
			}

			/**
			 * @param string $content
			 * @param int    $status
			 * @param array  $headers
			 *
			 * @return Response
			 */
			public function response(string $content, int $status = 200, array $headers = []): Response
			{
				return new Response($content, $status, $headers);
			}

			/**
			 *
			 * Return a view
			 *
			 * @param string $name
			 * @param array  $args
			 *
			 * @throws Kedavra
			 * @throws LoaderError
			 * @throws RuntimeError
			 * @throws SyntaxError
			 * @return string
			 *
			 */
			public function view(string $name, array $args = []): string
			{
				$this->session()->put('view', $name);

				return $this->view->load(get_called_class(), $name, $args);
			}

			/**
			 *
			 * Redirect user to a route
			 *
			 * @param string $route
			 * @param string $message
			 * @param bool   $success
			 *
			 * @throws Kedavra
			 *
			 * @return RedirectResponse
			 *
			 */
			public function redirect(string $route, string $message = '', bool $success = true): RedirectResponse
			{
				return redirect($route, $message, $success);
			}

			/**
			 *
			 * Redirect user back
			 *
			 * @param string $message
			 * @param bool   $success
			 *
			 * @throws Kedavra
			 *
			 * @return RedirectResponse
			 *
			 */
			public function back(string $message = '', bool $success = true): RedirectResponse
			{
				return back($message, $success);
			}

			/**
			 *
			 * Redirect user to an url
			 *
			 * @param string $url
			 * @param string $message
			 * @param bool   $success
			 *
			 * @throws Kedavra
			 * @return RedirectResponse
			 *
			 */
			public function to(string $url, string $message = '', bool $success = true): RedirectResponse
			{
				return to($url, $message, $success);
			}

			/**
			 *
			 * @param string $subject
			 * @param string $message
			 * @param string $author_email
			 * @param string $to
			 *
			 * @throws Kedavra
			 *
			 *
			 * @return Write
			 *
			 */
			public function write(string $subject, string $message, string $author_email, string $to): Write
			{
				return new Write($subject, $message, $author_email, $to);
			}

			/**
			 *
			 * Management of git
			 *
			 * @param string $repository
			 * @param string $owner
			 *
			 * @throws Kedavra
			 *
			 * @return Git
			 *
			 */
			public function git(string $repository, string $owner): Git
			{
				return new Git($repository, $owner);
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
				return new Validator($this->request()->request());
			}

			/**
			 *
			 * @return Model
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
			 * @param array  $args
			 *
			 * @throws Kedavra
			 * @return string
			 *
			 */
			public function url(string $route, ...$args): string
			{
				return route($route, $args);
			}

			/**
			 *
			 * Download a file
			 *
			 * @param string $filename
			 *
			 * @throws Kedavra
			 *
			 * @return Response
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
			 *
			 */
			public function display(string $table, string $column = '', string $expected = '', string $condition = DIFFERENT, string $order_by = DESC): string
			{
				return '';

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
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function records(string $table, string $column = '', string $expected = '', string $condition = DIFFERENT, string $order_by = DESC): array
			{
				return get_records($table, $column, $expected, $condition, $order_by);
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
				return json_encode($data, JSON_FORCE_OBJECT);
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
				return $this->cache;
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
				return server(ENV) === 'production';
			}

			/**
			 *
			 * Get the debug bar
			 *
			 * @throws Kedavra
			 * @return string
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
			 * @throws Kedavra
			 *
			 * @return File
			 *
			 */
			public function file(string $filename, string $mode = READ_FILE_MODE): File
			{
				return new File($filename, $mode);
			}

			/**
			 *
			 * @param string $filename
			 *
			 * @return Asset
			 *
			 */
			public function assets(string $filename): Asset
			{
				return new Asset($filename);
			}
		}
	}
