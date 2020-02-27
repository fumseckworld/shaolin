<?php

declare(strict_types=1);

namespace Eywa\Database\Model {

    use Exception;
    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connexion;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;

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
         * Web routes
         *
         */
        protected static bool $web = false;

        /**
         *
         * Admin routes
         *
         */
        protected static bool $admin = false;

        /**
         *
         * Task routes
         *
         */
        protected static bool $task = false;

        /**
         *
         * The project task
         */
        protected static bool $todo = false;

        /**
         *
         * The columns
         *
         */
        private Collect $column;


        /**
         *
         * Display all columns
         *
         * @return array
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
         * @return array
         *
         * @throws Kedavra
         */
        public static function all(int $pdo_style = \PDO::FETCH_OBJ) : array
        {
            return static::sql()->execute($pdo_style);
        }

        /**
         *
         * Create multiples records
         *
         * @param array $records
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
         * @return array
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
         * @return array
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
         * @param $columns
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
         * @return array
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
         * @param $value
         *
         * @throws Kedavra
         *
         */
        public function __set(string $name, $value)
        {
            $this->column->put($name, static::connection()->secure($value));

        }

        /**
         * Model constructor.
         */
        public function __construct()
        {
            $this->column = collect();
        }


        /**
         *
         * Update a record
         *
         * @param int $id
         * @param array $values
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
        public static function paginate($callable, int $current_page) : Sql
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
         *
         * @return array
         *
         * @throws Kedavra
         *
         */
        public static function find(int $id): array
        {
            return static::where(static::primary(),EQUAL,$id)->execute();
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
         * @return Connexion
         * @throws Kedavra
         *
         * @throws Exception
         */
        public static function connection(): Connexion
        {
            if (static::$admin)
                return connect(SQLITE, base('routes','admin.sqlite3'));

            if (static::$task)
                return connect(SQLITE, base('routes','task.sqlite3'));
            if (static::$todo)
                return connect(SQLITE, base('todo','todo.sqlite3'));

            if (static::$web)
                return connect(SQLITE, base('routes','web.sqlite3') );

            return app()->connexion();
        }

    }
}