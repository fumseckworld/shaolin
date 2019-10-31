<?php
	
	namespace Imperium\Model
	{
		
		use DI\DependencyException;
		use DI\NotFoundException;
		use Imperium\Exception\Kedavra;
		use Imperium\Query\Query;
        use Imperium\Zen;
        use stdClass;
		
		/**
		 * Class Model
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Model
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		abstract class Model extends Zen
		{
			
			/**
			 *
			 * The table associated with the model.
			 *
			 * @var string
			 *
			 */
			protected $table;
			
			/**
			 *
			 * The primary key for the model.
			 *
			 * @var string
			 *
			 */
			protected $primary = 'id';
			
			/**
			 *
			 * The column name used to find records
			 *
			 * @var string
			 *
			 */
			protected static $by = 'id';
			
			/**
			 *
			 * The per page limit
			 *
			 * @var int
			 *
			 */
			protected static $limit = 20;
			
			/**
			 *
			 * The sql query to create the table
			 *
			 * @var string
			 *
			 */
			protected static $create_route_table_query = "CREATE TABLE IF NOT EXISTS routes ( id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT(255) NOT NULL UNIQUE,url TEXT(255) NOT NULL UNIQUE, controller TEXT(255) NOT NULL,action TEXT(255) NOT NULL,method TEXT(255) NOT NULL);";

            protected static $create_todo = "CREATE TABLE IF NOT EXISTS  todo ( id INTEGER PRIMARY KEY AUTOINCREMENT , task TEXT(255) NOT NULL UNIQUE , description TEXT(255) NOT NULL , priority TEXT(255) NOT NULL ,due TEXT(255) NOT NULL);";

			/**
			 * @var bool
			 */
			protected $routes = false;

			/**
			 * @var bool
			 */
			protected $admin = false;

            /**
             * @var bool
             */
            protected $todo = false;

            /**
             * @var bool
             */
            protected $task = false;

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
				return static::query()->columns();
			}


            /**
             * @param int $begin
             * @param int $end
             * @param string $column
             *
             * @return Query
             *
             * @throws DependencyException
             * @throws NotFoundException
             *
             */
			public static function between(int $begin, int $end, string $column = '') : Query
			{
				$column = def($column) ? $column : self::key();
				
				return static::query()->between($column, $begin, $end);
			}

            /**
             * Undocumented function
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
				return static::query()->all();
			}
			
			/**
			 * @return string
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
				return static::query()->primary_key();
			}

            /**
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
				
				return self::by(self::key(), $expected);
			}


            /**
             *
             * Seed current table
             *
             * @param int $records
             *
             * @return bool
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             *
             */
            public static function seed(int $records): bool
            {
                $table = static::query()->table();

                $columns = static::columns();

                $columns_str = collect($columns)->join(',');

                $query = "INSERT INTO $table ($columns_str) VALUES ";

                $primary = static::primary();

                $x = collect();

                $sqlite = static::query()->connexion()->sqlite();


                $types = collect(static::types());

                for($i=0;different($i,$records);$i++)
                {
                    foreach ($columns as $k => $column)
                    {
                        $type = $sqlite ? strtolower($types->get($k)) : $types->get($k);

                        if (equal($column,$primary))
                        {
                            switch (static::query()->connexion()->driver())
                            {
                                case MYSQL:
                                case SQLITE:
                                    $x->put($column,'NULL');
                                break;
                                default:
                                    $x->put($column,'DEFAULT');
                                break;
                            }
                        }
                        else
                        {

                            if (has($type, self::BOOL))
                            {
                                $number = rand(0,1);
                                $number == 0 ? $x->put($column,'false') : $x->put($column,'true');
                            }


                            if (has($type, self::JSONS))
                            {
                                $data = collect();

                                $number = rand(1,10);

                                for ($i=0; $i < $number ; $i++)
                                {
                                    if(is_pair($i))
                                        $x->put($i,static::query()->connexion()->pdo()->quote(faker()->text(50)));
                                    else
                                        $data->put($i,faker()->numberBetween(1,50));
                                }
                                $x->put($column,$data->json());

                            }
                            if (has($type,self::DATE_TYPES))
                                $x->put($column,faker()->date());

                            if (has($type,self::NUMERIC_TYPES))
                                $x->put($column,faker()->numberBetween(1,100));

                            if (has($type,self::TEXT_TYPES))
                            {
                                $x->put($column,static::query()->connexion()->pdo()->quote(faker()->text(50)));
                            }

                        }

                    }


                    $value = '(' .$x->join(', ') . '),';

                    append($query,$value);

                    $x->clear();
                }
                $query = trim($query,',');

                return  static::query()->connexion()->execute($query);

            }

            /**
             *
             * Get all available types for current driver
             *
             * @method types
             *
             * @return array
             *
             * @throws DependencyException
             * @throws NotFoundException
             *
             */
            public static function types() : array
            {
                switch (static::query()->connexion()->driver())
                {
                    case MYSQL:
                        return self::MYSQL_TYPES;
                    break;
                    case POSTGRESQL:
                        return self::POSTGRESQL_TYPES;
                    break;
                    case SQLITE:
                        return self::SQLITE_TYPES;
                    break;
                    default:
                        return [];
                    break;
                }
            }

            /**
             *
             * @param string $column
             * @param mixed $expected
             *
             * @return object
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             */
			public static function by(string $column, $expected) : object
			{
				
				$x = static::query()->where($column, EQUAL, $expected)->fetch(true)->all();
				
				return is_object($x) ? $x : new stdClass();
			}

            /**
             *
             * Get only column values
             *
             * @param string ...$columns
             *
             * @return array
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             *
             */
			public static function only(string ...$columns) : array
			{
				return static::query()->select($columns)->all();
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
				return static::query()->like($x)->all();
			}

            /**
             *
             *
             * Add a new record
             *
             * @param array $values
             *
             * @return bool
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             *
             */
			public static function create(array $values) : bool
			{
				$x = collect(static::query()->columns())->join(',');
				
				$values = collect($values)->for('htmlentities')->all();
				
				$table = static::query()->table();
				
				$id = static::query()->key();
				
				$sql = "INSERT INTO $table ($x) VALUES (";
				
				foreach(static::query()->columns() as $column)
					equal($column, $id) ? static::query()->connexion()->postgresql() ? append($sql, 'DEFAULT, ') : append($sql, 'NULL, ') : append($sql, static::query()->connexion()->pdo()->quote(collect($values)->get($column)) . ', ');
				
				$sql = trim($sql, ', ');
				
				append($sql, ')');
				
				return static::query()->connexion()->execute($sql);
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
                $primary = static::query()->primary_key();

                $columns = collect();

                $table = static::query()->table();

                foreach ($values  as $k => $value)
                {
                    if (different($k,$primary))
                        $columns->push("$k =" .static::query()->connexion()->pdo()->quote($value));

                }

                $columns =  $columns->join(', ');

                $command = "UPDATE  $table SET $columns WHERE $primary = $id";

                return  static::query()->connexion()->execute($command);
            }

            /**
             *
             * Display all records with a pagination
             *
             * @param callable $callable
             * @param int $current_page
             *
             * @return Query
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             *
             */
			public static function paginate($callable, int $current_page) : Query
			{
				return static::query()->paginate($callable, $current_page, static::$limit);
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
				return static::query()->destroy($id);
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
				return static::query()->sum();
			}

            /**
             *
             * Generate a clause where
             *
             * @param string $column
             * @param string $condition
             * @param mixed $expected
             *
             * @return Query
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             *
             */
			public static function where(string $column, string $condition, $expected) : Query
			{
				return static::query()->mode(SELECT)->where($column, $condition, $expected);
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
			public static function find(int $id)
			{
				return static::query()->find($id);
			}

            /**
             *
             * Use a different where clause
             *
             * @param mixed $expected
             *
             * @param string $column
             *
             * @return Query
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             *
             */
			public static function different($expected, string $column = '') : Query
			{
				
				$column = def($column) ? $column : self::key();
				
				return self::where($column, DIFFERENT, $expected);
			}

            /**
             *
             * Begin querying the model.
             *
             * @return Query
             *
             * @throws DependencyException
             * @throws NotFoundException
             *
             */
			public static function query() : Query
			{
				return (new static)->builder();
			}
			
			/**
			 *
			 * @throws DependencyException
			 * @throws NotFoundException
             *
			 * @return Query
			 *
			 */
			private function builder() : Query
			{
				return Query::from($this->table, $this->routes,$this->admin,$this->todo,$this->task)->primary($this->primary);
			}
			
		}
	}


