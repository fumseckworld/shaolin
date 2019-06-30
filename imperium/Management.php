<?php

namespace Imperium {

    use Exception;


    use Imperium\Bases\Base;
    use Imperium\Cache\Cache;
    use Imperium\Collection\Collection;
    use Imperium\Config\Config;
    use Imperium\Connexion\Connect;
    use Imperium\Exception\Kedavra;
    use Imperium\Json\Json;
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
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

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
        public function all(string $table,string $column,string $order = DESC): array;

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
         * @param mixed $data
         *
         * @return Collection
         *
         */
        public function collection($data = []): Collection;



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
         * @param bool $nullable
         *
         * @return bool
         *
         */
        public function append_column(string $table,string $column, string $type, int $size, bool $nullable): bool;

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
         * @return object
         *
         */
        public function find(string $table,int $id);

        /**
         *
         * Find a record or fail if not found
         *
         * @param string $table
         * @param int $id
         *
         * @return object
         *
         */
        public function find_or_fail(string $table,int $id);


        /**
         *
         * Save the data in a table
         *
         * @param string $table
         * @param array $data
         *
         * @return bool
         *
         */
        public function save(string $table,array $data) : bool;

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
         * @return Config
         */
        public function config(): Config;

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
         *
         */
        public function write(string $subject, string $message, string $author_email, string $to): Write;


        /**
         *
         * Return a view
         *
         * @param string $name
         * @param array $args
         *
         * @return string
         *
         */
        public function view(string $name,array $args = []): string;

        /**
         *
         * Dump a base or  tables
         *
         * @param bool $base
         * @param string ...$tables
         *
         * @return bool
         */
        public function dump(bool $base,string ...$tables) : bool;


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
        public function redirect(string $route,string $message ='',bool $success = true): RedirectResponse;

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
        public function back(string $message ='',bool $success = true): RedirectResponse;


        /**
         *
         * Json management
         *
         * @param string $filename
         * @param string $mode
         *
         * @return Json
         *
         * @throws Kedavra
         *
         */
        public function json(string $filename,string $mode = EMPTY_AND_WRITE_FILE_MODE): Json;


        /**
         *
         *
         * @param array $data
         *
         * @return JsonResponse
         *
         */
        public function json_response(array $data):JsonResponse;

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
        public function to(string $url,string $message = '',bool $success = true): RedirectResponse;

        /**
         *
         * Check if mode is enabled in production
         *
         * @return bool
         *
         */
        public function production(): bool ;

        /**
         *
         * @return Response
         *
         */
        public function response(): Response;


        /**
         * @return Model
         */
        public function route() : Model;


        /**
         *
         * Get cache instance
         *
         * @return Cache
         *
         */
        public function cache() : Cache;

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
        public function records(string $table,string $column ='',string $expected = '',string $condition = DIFFERENT,string $order_by = DESC): array ;

        /**
         *
         * Encode data to json
         *
         * @param array $data
         *
         * @return string
         *
         */
        public function encode(array $data): string ;

        /**
         * @param string $table
         * @param string $column
         * @param string $expected
         * @param string $condition
         * @param string $order_by
         * @return string
         */
        public function display(string $table,string $column ='',string $expected = '',string $condition = DIFFERENT,string $order_by = DESC): string ;

        /**
         *
         * Download a file
         *
         * @param string $filename
         *
         * @return Response
         *
         */
        public function download(string $filename) : Response;


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
         * @param mixed $args
         *
         * @return string
         */
        public function url(string $route,...$args): string ;

        // GETTER

        // END GETTER
    }
}
