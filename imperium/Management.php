<?php

	namespace Imperium
	{

		use Exception;
		use Imperium\Asset\Asset;
		use Imperium\Bases\Base;
		use Imperium\Cache\Cache;
		use Imperium\Collection\Collect;
		use Imperium\Connexion\Connect;
		use Imperium\Exception\Kedavra;
		use Imperium\File\File;
		use Imperium\Json\Json;
		use Imperium\Request\Request;
		use Imperium\Validator\Validator;
		use Imperium\Versioning\Git\Git;
		use Imperium\Writing\Write;
		use Imperium\Flash\Flash;
		use Imperium\Html\Form\Form;
		use Imperium\Model\Model;
		use Imperium\Query\Query;
		use Imperium\Routing\Router;
		use Imperium\Security\Auth\Oauth;
		use Imperium\Session\SessionInterface;
		use Imperium\Tables\Table;
		use Imperium\Users\Users;
		use Psr\Http\Message\ServerRequestInterface;
		use Symfony\Component\HttpFoundation\JsonResponse;
		use Symfony\Component\HttpFoundation\RedirectResponse;
		use Symfony\Component\HttpFoundation\Response;

		/**
		 * Interface Management
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
		interface Management
		{
			/**
			 *
			 * Display all tables
			 *
			 *
			 * @throws Exception
			 * @return array
			 *
			 */
			public function show_tables(): array;

			/**
			 *
			 * Display all users
			 *
			 *
			 * @throws Exception
			 *
			 * @return array
			 *
			 */
			public function show_users(): array;

			/**
			 *
			 * Get a config value
			 *
			 * @param string $file
			 * @param        $key
			 *
			 * @throws Kedavra
			 *
			 * @return mixed
			 *
			 */
			public function config(string $file, $key);

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
			public function file(string $filename, string $mode = READ_FILE_MODE): File;

			/**
			 *
			 * Display all bases
			 *
			 * @throws Exception
			 *
			 * @return array
			 *
			 */
			public function show_databases(): array;

			/**
			 *
			 * Display all charsets available
			 *
			 * @throws Exception
			 *
			 * @return array
			 *
			 */
			public function charsets(): array;

			/**
			 *
			 * Display all collation available
			 *
			 * @throws Exception
			 *
			 * @return array
			 *
			 */
			public function collations(): array;


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
			public function all(string $table, string $column, string $order = DESC): array;

			/**
			 *
			 * Check if a table exist
			 *
			 * @param string $table
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function table_exist(string $table): bool;

			/**
			 *
			 * Check if a base exist
			 *
			 * @param string $base
			 *
			 * @return bool
			 *
			 */
			public function base_exist(string $base): bool;

			/**
			 *
			 * Change base collation
			 *
			 * @param string $new_collation
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function change_base_collation(string $new_collation): bool;

			/**
			 *
			 * Change table collation
			 *
			 * @param string $table
			 * @param string $new_collation
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function change_table_collation(string $table, string $new_collation): bool;

			/**
			 *
			 * Change base charset
			 *
			 * @param string $new_charset
			 *
			 * @return bool
			 *
			 */
			public function change_base_charset(string $new_charset): bool;

			/**
			 *
			 * Management iof the array
			 *
			 * @param mixed $data
			 *
			 * @return Collect
			 *
			 */
			public function collection($data = []): Collect;


			/**
			 *
			 * Change table charset
			 *
			 * @param string $table
			 * @param string $new_charset
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function change_table_charset(string $table, string $new_charset): bool;

			/**
			 *
			 * Check if a user exist
			 *
			 * @param string $user
			 *
			 * @throws Exception
			 * @return bool
			 *
			 */
			public function user_exist(string $user): bool;


			/**
			 *
			 * Remove a table
			 *
			 * @param string $table
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function remove_table(string $table): bool;

			/**
			 *
			 * Empty all records in a table
			 *
			 * @param string $table
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function empty_table(string $table): bool;

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
			 * @return bool
			 *
			 */
			public function append_column(string $table, string $column, string $type, int $size, bool $nullable): bool;

			/**
			 *
			 * Display all columns in a table
			 *
			 * @param string $table
			 *
			 * @return array
			 *
			 */
			public function show_columns(string $table): array;

			/**
			 *
			 * Display all column types
			 *
			 * @param string $table
			 *
			 * @return array
			 *
			 */
			public function show_columns_types(string $table): array;


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
			public function has_column(string $table, string $column): bool;

			/**
			 *
			 * Check if current base has table
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function has_tables(): bool;

			/**
			 *
			 * Check if server has bases
			 *
			 * @throws Exception
			 * @return bool
			 *
			 */
			public function has_bases(): bool;

			/**
			 *
			 * Check if server has users
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function has_users(): bool;

			/**
			 *
			 * Create a new database
			 *
			 * @param string $name
			 * @param string $charset
			 *
			 * @param string $collation
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function add_database(string $name, string $charset = '', string $collation = ''): bool;

			/**
			 *
			 * Remove a database
			 *
			 * @param string $name
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function remove_database(string $name): bool;

			/**
			 *
			 * Create a new user
			 *
			 * @param string $name
			 * @param string $password
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function add_user(string $name, string $password): bool;

			/**
			 *
			 * Change user password
			 *
			 * @param string $name
			 * @param string $password
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function change_user_password(string $name, string $password): bool;

			/**
			 *
			 * Remove an user
			 *
			 * @param string $name
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function remove_user(string $name): bool;

			/**
			 *
			 * Find a record by id
			 *
			 * @param string $table
			 * @param int    $id
			 *
			 * @return object
			 *
			 */
			public function find(string $table, int $id);

			/**
			 *
			 * Find a record or fail if not found
			 *
			 * @param string $table
			 * @param int    $id
			 *
			 * @return object
			 *
			 */
			public function find_or_fail(string $table, int $id);


			/**
			 *
			 * Save the data in a table
			 *
			 * @param string $table
			 * @param array  $data
			 *
			 * @return bool
			 *
			 */
			public function save(string $table, array $data): bool;

			/**
			 *
			 * Save the data in a table
			 *
			 * @param string $table
			 * @param int    $id
			 *
			 * @return bool
			 *
			 */
			public function remove_record(string $table, int $id): bool;

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
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function update_record(int $id, array $data, string $table, array $ignore): bool;


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
			public function rename_column(string $table, string $column, string $new_name): bool;

			/**
			 *
			 * Rename a table
			 *
			 * @param string $table
			 * @param string $new_name
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function rename_table(string $table, string $new_name): bool;

			/**
			 *
			 * Rename a base
			 *
			 * @param string $base
			 * @param string $new_name
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function rename_base(string $base, string $new_name): bool;


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
			public function remove_column(string $table, string $column): bool;

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
			 *
			 * Management of git
			 *
			 * @param string $repository
			 * @param string $owner
			 *
			 * @return Git
			 *
			 */
			public function git(string $repository, string $owner): Git;

			/**
			 * @return SessionInterface
			 */
			public function session(): SessionInterface;

			/**
			 * @return Request
			 */
			public function request(): Request;


			/**
			 * @return Oauth
			 */
			public function auth(): Oauth;

			/**
			 *
			 * @param ServerRequestInterface $serverRequest
			 *
			 * @return Router
			 *
			 */
			public function router(ServerRequestInterface $serverRequest): Router;

			/**
			 * @param string $filename
			 *
			 * @return Asset
			 *
			 */
			public function assets(string $filename): Asset;


			/**
			 *
			 * @param string $subject
			 * @param string $message
			 * @param string $author_email
			 * @param string $to
			 *
			 * @throws Exception
			 *
			 *
			 * @return Write
			 *
			 */
			public function write(string $subject, string $message, string $author_email, string $to): Write;


			/**
			 *
			 * Return a view
			 *
			 * @param string $name
			 * @param array  $args
			 *
			 * @return string
			 *
			 */
			public function view(string $name, array $args = []): string;

			/**
			 *
			 * Dump a base or  tables
			 *
			 * @param bool   $base
			 * @param string ...$tables
			 *
			 * @return bool
			 */
			public function dump(bool $base, string ...$tables): bool;


			/**
			 *
			 * Redirect user to a route
			 *
			 * @param string $route
			 * @param string $message
			 * @param bool   $success
			 *
			 * @throws Exception
			 *
			 * @return RedirectResponse
			 *
			 */
			public function redirect(string $route, string $message = '', bool $success = true): RedirectResponse;

			/**
			 *
			 * Redirect user back
			 *
			 * @param string $message
			 * @param bool   $success
			 *
			 * @return RedirectResponse
			 *
			 */
			public function back(string $message = '', bool $success = true): RedirectResponse;


			/**
			 *
			 * Json management
			 *
			 * @param string $filename
			 * @param string $mode
			 *
			 * @throws Kedavra
			 *
			 * @return Json
			 *
			 */
			public function json(string $filename, string $mode = EMPTY_AND_WRITE_FILE_MODE): Json;


			/**
			 *
			 *
			 * @param array $data
			 *
			 * @return JsonResponse
			 *
			 */
			public function json_response(array $data): JsonResponse;

			/**
			 *
			 * Redirect user to an url
			 *
			 * @param string $url
			 * @param string $message
			 * @param bool   $success
			 *
			 * @return RedirectResponse
			 *
			 */
			public function to(string $url, string $message = '', bool $success = true): RedirectResponse;

			/**
			 *
			 * Check if mode is enabled in production
			 *
			 * @return bool
			 *
			 */
			public function production(): bool;

			/**
			 *
			 * Get the debug bar
			 *
			 * @return string
			 *
			 */
			public function debug_bar(): string;

			/**
			 *
			 * @param string $content
			 * @param int    $status
			 * @param array  $headers
			 *
			 * @return Response
			 */
			public function response(string $content, int $status = 200, array $headers = []): Response;


			/**
			 * @return Model
			 */
			public function route(): Model;


			/**
			 *
			 * Get cache instance
			 *
			 * @return Cache
			 *
			 */
			public function cache(): Cache;

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
			public function records(string $table, string $column = '', string $expected = '', string $condition = DIFFERENT, string $order_by = DESC): array;

			/**
			 *
			 * Encode data to json
			 *
			 * @param array $data
			 *
			 * @return string
			 *
			 */
			public function encode(array $data): string;

			/**
			 * @param string $table
			 * @param string $column
			 * @param string $expected
			 * @param string $condition
			 * @param string $order_by
			 *
			 * @return string
			 */
			public function display(string $table, string $column = '', string $expected = '', string $condition = DIFFERENT, string $order_by = DESC): string;

			/**
			 *
			 * Download a file
			 *
			 * @param string $filename
			 *
			 * @return Response
			 *
			 */
			public function download(string $filename): Response;


			/**
			 *
			 * Check the request
			 *
			 * @return RedirectResponse|Validator
			 *
			 */
			public function validator();

			/**
			 *
			 * Generate url string
			 *
			 * @param string $route
			 * @param mixed  $args
			 *
			 * @return string
			 */
			public function url(string $route, ...$args): string;

			// GETTER

			// END GETTER
		}
	}
