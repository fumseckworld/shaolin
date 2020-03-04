<?php

declare(strict_types=1);

namespace Eywa\Database\Model {

    use Exception;
    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Connexion\Connexion;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use PDO;

    abstract class Model
    {

        /**
         *
         * The table associated with the model.
         *
         */
        protected static string $table = '';

        /**
         *
         * The column name used to find records
         *
         */
        protected static string $by = 'id';

        /**
         *
         * The per page limit
         *
         */
        protected static int $limit = 20;

        /**
         *
         * The sql Sql to create the table
         *
         */
        protected static string $create_route_table_query = "CREATE TABLE IF NOT EXISTS routes ( id INTEGER PRIMARY KEY AUTOINCREMENT, method TEXT(255) NOT NULL, name TEXT(255) NOT NULL UNIQUE,url TEXT(255) NOT NULL UNIQUE, controller TEXT(255) NOT NULL,action TEXT(255) NOT NULL, directory TEXT(255) NOT NULL,created_at DATETIME ,updated_at DATETIME);";

        /**
         *
         * To create toto table
         *
         */
        protected static string $create_todo = "CREATE TABLE IF NOT EXISTS  todo ( id INTEGER PRIMARY KEY AUTOINCREMENT , task TEXT(255) NOT NULL UNIQUE , description TEXT(255) NOT NULL , priority TEXT(255) NOT NULL ,due TEXT(255) NOT NULL);";


        /**
         *
         * For router web routes
         *
         */
        protected static bool $web = false;

        /**
         *
         * The columns
         *
         */
        private Collect $column;

        /**
         * Model constructor.
         */
        public function __construct()
        {
            $this->column = collect();
        }

        /**
         *
         * Function executed before the validator
         *
         * @param Request $request
         *
         * @return bool
         *
         */
        abstract public function before_validation(Request $request): bool;

        /**
         *
         * Function executed after the validation
         *
         * @param Request $request
         *
         * @return bool
         *
         */
        abstract public function after_validation(Request $request):bool ;


        /**
         *
         * Function executed before save a new reoord
         *
         * @param Request $request
         *
         * @return bool
         *
         */
        abstract public function before_save(Request $request): bool;

        /**
         *
         * Function executed after data has been saved
         *
         * @param Request $request
         *
         * @return bool
         *
         */
        abstract public function after_save(Request $request): bool;

        /**
         *
         * @param Request $request
         *
         * @return bool
         *
         */
        abstract public function after_commit(Request $request): bool;

        /**
         *
         *
         * @param Request $request
         *
         * @return bool
         *
         */
        abstract public function after_rollback(Request $request): bool;


        /**
         *
         * Function executed executed before update a record
         *
         * @param Request $request
         *
         * @return bool
         *
         */
        abstract public function before_update(Request $request): bool;

        /**
         *
         * Function executed after record has been updated
         *
         * @param Request $request
         *
         * @return bool
         *
         */
        abstract public function after_update(Request $request):bool;

        /**
         *
         * Function executed before create a new record
         *
         * @param Request $request
         *
         * @return bool
         *
         */
        abstract public function before_create(Request $request): bool;

        /**
         *
         * Function executed after create a new record
         *
         * @param Request $request
         *
         * @return bool
         *
         */
        abstract public function after_create(Request $request): bool;

        /**
         *
         * @param Request $request
         *
         * @return bool
         *
         */
        abstract public function before_destroy(Request $request): bool;

        /**
         *
         * @param Request $request
         *
         * @return bool
         *
         */
        abstract public function after_destroy(Request $request): bool;

        /**
         *
         * Display all columns
         *
         * @return array<string>
         *
         * @throws Kedavra
         *
         */
        public static function columns() : array
        {
            return static::sql()->from(static::$table)->columns();
        }

        /**
         *
         * Generate a between clause
         *
         * @param int $begin
         * @param int $end
         * @param string $column
         *
         * @return Sql
         *
         * @throws Kedavra
         *
         */
        public static function between(int $begin, int $end, string $column = '') : Sql
        {
            $column = def($column) ? $column : self::key();

            return static::sql()->between($column, $begin, $end);
        }

        /**
         *
         * Get all records
         *
         * @param int $pdo_style
         *
         * @return array<mixed>
         *
         * @throws Kedavra
         */
        public static function all(int $pdo_style = PDO::FETCH_OBJ) : array
        {
            return static::sql()->execute($pdo_style);
        }

        /**
         *
         * Create multiples records
         *
         * @param array<mixed> $records
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public static function create(array $records): bool
        {
            return static::sql()->from(static::$table)->save($records);
        }

        /**
         *
         * Remove records
         *
         * @param int ...$ids
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public static function remove(int ...$ids): bool
        {
            $result = collect();
            foreach ($ids as $id)
                $result->push(static::where(static::primary(),EQUAL,$id)->destroy());

            return $result->ok();
        }

        /**
         *
         * The key defined in the model used for only methods
         *
         * @return string
         *
         */
        public static function key() : string
        {
            return static::$by;
        }

        /**
         *
         * Display the primary key
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public static function primary() : string
        {
            return static::sql()->from(static::$table)->primary();
        }

        /**
         *
         * Get a record
         *
         * @param mixed $expected
         *
         * @return array<mixed>
         *
         * @throws Kedavra
         *
         */
        public static function get($expected) : array
        {
            is_true(not_def($expected), true, "Missing the expected value");

            return static::by(self::key(), $expected);
        }

        /**
         *
         * Get a record by a column
         *
         * @param string $column
         * @param mixed $expected
         *
         * @return array<mixed>
         *
         * @throws Kedavra
         *
         */
        public static function by(string $column, $expected) : array
        {
            return static::sql()->where($column, EQUAL, $expected)->execute();
        }

        /**
         *
         * Get only column values
         *
         * @param array<int,string> $columns
         *
         * @return Sql
         *
         * @throws Kedavra
         *
         */
        public static function only(string ...$columns) : Sql
        {
            return static::sql()->only($columns);
        }

        /**
         *
         * Search a value
         *
         * @param string $x
         *
         * @return array<mixed>
         *
         * @throws Kedavra
         *
         */
        public static function search(string $x): array
        {
            return static::sql()->like($x)->execute();
        }


        /**
         *
         * Add a new record
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function save() : bool
        {
            $x = collect(static::columns())->join();

            $table = static::$table;

            $id = static::primary();

            $sql = "INSERT INTO $table ($x) VALUES (";

            foreach(static::columns() as $column)
                equal($column, $id) ? static::connection()->postgresql() ? append($sql, 'DEFAULT, ') : append($sql, 'NULL, ') : append($sql, $this->column->get($column) . ', ');

            $sql = trim($sql, ', ');

            append($sql, ')');

            $this->column->clear();

            return static::connection()->set($sql)->execute();
        }

        /**
         *
         * Update a record
         *
         * @param int $id
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function refresh(int $id): bool
        {

            $table = static::$table;

            $primary = static::primary();

            $sql = "UPDATE $table SET";

            foreach($this->column->all() as $k => $v)
                append($sql, "  $k = $v ,");

            $sql = trim($sql,',');

            $sql .= " WHERE $primary = $id";

            $this->column->clear();

            return static::connection()->set($sql)->execute();
        }

        /**
         *
         * @param string $name
         * @param mixed $value
         *
         * @throws Kedavra
         *
         */
        public function __set(string $name, $value): void
        {
            $this->column->put($name, static::connection()->secure($value));

        }



        /**
         *
         * Update a record
         *
         * @param int $id
         * @param array<mixed> $values
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public static function update(int $id,array $values): bool
        {
            $primary = static::primary();

            $columns = collect();

            $table = static::$table;

            foreach ($values  as $k => $value)
            {
                if (different($k,$primary))
                    $columns->push("$k =" .static::connection()->secure($value));

            }

            $columns =  $columns->join(', ');

            $command = "UPDATE $table SET $columns WHERE $primary = $id";

            return  static::connection()->set($command)->execute();
        }

        /**
         *
         * Display all records with a pagination
         *
         * @param callable $callable
         * @param int $current_page
         *
         * @return Sql
         *
         * @throws Kedavra
         *
         */
        public static function paginate(callable $callable, int $current_page) : Sql
        {
            return static::sql()->paginate($callable, $current_page, static::$limit);
        }

        /**
         *
         * Destroy a record by id
         *
         * @param int $id
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public static function destroy(int $id) : bool
        {
            return static::where(static::primary(),EQUAL,$id)->destroy();
        }

        /**
         *
         * Count record in a table
         *
         * @return int
         *
         * @throws Kedavra
         *
         */
        public static function sum() : int
        {
            return static::sql()->sum();
        }

        /**
         *
         * Generate a clause where
         *
         * @param string $column
         * @param string $condition
         * @param mixed $expected
         *
         * @return Sql
         *
         * @throws Kedavra
         *
         */
        public static function where(string $column, string $condition, $expected) : Sql
        {
            return static::sql()->where($column, $condition, $expected);
        }

        /**
         *
         * Find a record by an id
         *
         * @param int $id
         * @param int $pdo_fetch_mode
         *
         * @return array<mixed>
         *
         * @throws Kedavra
         */
        public static function find(int $id,int $pdo_fetch_mode = PDO::FETCH_OBJ): array
        {
            return static::where(static::primary(),EQUAL,$id)->execute($pdo_fetch_mode);
        }

        /**
         *
         * Use a different where clause
         *
         * @param mixed $expected
         *
         * @param string $column
         *
         * @return Sql
         *
         * @throws Kedavra
         *
         */
        public static function different($expected, string $column = '') : Sql
        {
            $column = def($column) ? $column : self::key();

            return static::where($column, DIFFERENT, $expected);
        }

        /**
         *
         * @return Sql
         *
         * @throws Kedavra
         *
         */
        public static function sql(): Sql
        {
            return (new Sql(static::connection(),static::$table));
        }


        /**
         *
         * @return Connect
         *
         * @throws Kedavra
         *
         * @throws Exception
         *
         */
        public static function connection(): Connect
        {

            if (static::$web)
                return connect(SQLITE, base('routes','web.sqlite3'));

            return app()->connexion();
        }

    }
}