<?php

declare(strict_types=1);

namespace Eywa\Database\Model {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Collection\Collect;
    use Eywa\Database\Connection\Connect;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Zen;

    abstract class Model
    {

        /**
         *
         * The table associated with the model.
         *
         */
        protected static string $table;

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
        protected static string $create_route_table_query = "CREATE TABLE IF NOT EXISTS routes ( id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT(255) NOT NULL UNIQUE,url TEXT(255) NOT NULL UNIQUE, controller TEXT(255) NOT NULL,action TEXT(255) NOT NULL,method TEXT(255) NOT NULL);";

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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public static function columns() : array
        {
            return static::sql()->columns();
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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
         * @return array
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public static function all() : array
        {
            return static::sql()->all();
        }

        /**
         *
         * Create multiples records
         *
         * @param array ...$records
         * @return bool
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public static function create(array ...$records)
        {

            $table = static::$table;
            $x = collect(static::columns())->join();
            $sql = "INSERT INTO $table ($x) VALUES ";

            $id = static::connection()->postgresql() ? 'DEFAULT' : 'NULL';
            foreach ($records as $record)
            {
                append($sql,'(',$id,',');

                foreach ($record as $k => $v)
                {
                   append($sql,static::connection()->pdo()->quote($v),',');
                }

                $sql = trim($sql,',');

                append($sql,')',',');


            }
            $sql = trim($sql,',');

            return static::connection()->execute($sql);
        }

        /**
         *
         * Remove records
         *
         * @param int ...$ids
         *
         * @return bool
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public static function primary() : string
        {
            return static::sql()->primary();
        }

        /**
         *
         * Get a record
         *
         * @param mixed $expected
         *
         * @return object
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public static function get($expected) : object
        {
            is_true(not_def($expected), true, "Missing the expected value");

            return static::by(self::key(), $expected);
        }

        /**
         *
         * Get all available types for current driver
         *
         * @return array
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         */
        public static function types() : array
        {
            switch (static::connection()->driver())
            {
                case MYSQL:
                    return Zen::MYSQL_TYPES;
                break;
                case POSTGRESQL:
                    return Zen::POSTGRESQL_TYPES;
                break;
                case SQLITE:
                    return Zen::SQLITE_TYPES;
                break;
                default:
                    return Zen::ALL_TYPES;
                break;
            }
        }

        /**
         *
         * Get a record by a column
         *
         * @param string $column
         * @param mixed $expected
         *
         * @return object
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public static function by(string $column, $expected) : object
        {
            return static::sql()->where($column, EQUAL, $expected)->fetch(true)->all();
        }

        /**
         *
         * Get only column values
         *
         * @param string ...$columns
         *
         * @return Sql
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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
         * @return mixed
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public static function search(string $x)
        {
            return static::sql()->like($x)->all();
        }


        /**
         *
         * Add a new record
         *
         * @return bool
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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

            return static::connection()->execute($sql);
        }

        /**
         *
         * Update a record
         *
         * @param int $id
         *
         * @return bool
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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

            return static::connection()->execute($sql);
        }

        /**
         *
         * @param string $name
         * @param $value
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public function __set(string $name, $value)
        {
            $this->column->put($name, static::connection()->pdo()->quote($value));

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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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
                    $columns->push("$k =" .static::connection()->pdo()->quote($value));

            }

            $columns =  $columns->join(', ');

            $command = "UPDATE $table SET $columns WHERE $primary = $id";

            return  static::connection()->execute($command);
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public static function count() : int
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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
         * @return object
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public static function find(int $id): object
        {
            return static::where(static::primary(),EQUAL,$id)->fetch(true)->all();
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public static function sql(): Sql
        {
            return (new Sql(static::connection(),static::$table));
        }

        /**
         *
         * Get automatic connection
         *
         * @return Connect
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         */
        public static function connection(): Connect
        {
            if (static::$admin)
                return connect(SQLITE, base('routes') . DIRECTORY_SEPARATOR . 'admin.sqlite3');

            if (static::$task)
                return connect(SQLITE, base('routes') . DIRECTORY_SEPARATOR . 'task.sqlite3');
            if (static::$todo)
                return connect(SQLITE, base('todo') . DIRECTORY_SEPARATOR . 'todo.sqlite3');

            return  connect(env('DB_DRIVER'),env('DB_NAME'),env('DB_USERNAME'),env('DB_PASSWORD'),env('DB_HOST'),intval(env('DB_PORT')),'dump');
        }

    }
}