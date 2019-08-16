<?php
	
	namespace Imperium\Model
	{
		
		use DI\DependencyException;
		use DI\NotFoundException;
		use Imperium\Exception\Kedavra;
		use Imperium\Query\Query;
		
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
		abstract class Model
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
			protected $create_route_table_query = "CREATE TABLE IF NOT EXISTS routes ( id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT(255) NOT NULL UNIQUE,url TEXT(255) NOT NULL UNIQUE, controller TEXT(255) NOT NULL,action TEXT(255) NOT NULL,method TEXT(255) NOT NULL);";
			
			/**
			 * @var bool
			 */
			protected $routes = false;
			
			/**
			 *
			 * Display all columns
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public static function columns() : array
			{
				
				return static::query()->columns();
			}
			
			/**
			 * @param  int     $begin
			 * @param  int     $end
			 * @param  string  $column
			 *
			 * @throws Kedavra
			 * @return Query
			 */
			public static function between(int $begin, int $end, string $column = '') : Query
			{
				
				$column = def($column) ? $column : self::key();
				
				return static::query()->between($column, $begin, $end);
			}
		
			/**
			 * Undocumented function
			 *
			 * @throws Kedavra
			 *
			 * @return array
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
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public static function primary() : string
			{
				
				return static::query()->primary_key();
			}
			
			/**
			 *
			 * @param  mixed  $expected
			 *
			 * @throws Kedavra
			 *
			 * @return object
			 *
			 */
			public static function get($expected) : object
			{
				
				is_true(not_def($expected), true, "Missing the expected value");
				
				return self::by(self::key(), $expected);
			}
			
			/**
			 *
			 * @param  string  $column
			 * @param  mixed   $expected
			 *
			 * @throws Kedavra
			 *
			 * @return object
			 *
			 */
			public static function by(string $column, $expected) : object
			{
				
				$x = static::query()->where($column, EQUAL, $expected)->fetch(true)->all();
				
				return is_object($x) ? $x : new \stdClass();
			}
			
			/**
			 *
			 * Get only column values
			 *
			 * @param  string  $column
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public static function only(string $column) : array
			{
				
				$x = collect();
				foreach(static::query()->select($column)->all() as $data)
					$x->push($data->$column);
				
				return $x->all();
			}
			
			/**
			 *
			 *
			 * @param  string  $x
			 *
			 * @throws Kedavra
			 *
			 * @return mixed
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
			 * @param  array  $values
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public static function create(array $values) : bool
			{
				
				$x = collect(static::query()->columns())->join(',');
				$values = collect($values)->for('htmlspecialchars')->all();
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
			 * Display all records with a pagination
			 *
			 * @param  callable  $callable
			 * @param  int       $current_page
			 *
			 * @throws Kedavra
			 * @return string
			 */
			public static function paginate($callable, int $current_page) : string
			{
				return static::query()->paginate($callable, $current_page, static::$limit);
			}
			
			/**
			 *
			 * Destroy a record by id
			 *
			 * @param  int  $id
			 *
			 * @throws Kedavra
			 *
			 * @return bool
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
			 * @throws Kedavra
			 *
			 * @return int
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
			 * @param  string  $column
			 * @param  string  $condition
			 * @param  mixed   $expected
			 *
			 * @throws Kedavra
			 * @return Query
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
			 * @param  int  $id
			 *
			 * @throws Kedavra
			 *
			 * @return object
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
			 * @param  string  $column
			 * @param  mixed   $expected
			 *
			 * @throws Kedavra
			 *
			 * @return Query
			 *
			 */
			public static function different($expected, string $column = '') : Query
			{
				
				$column = def($column) ? $column : self::key();
				
				return self::where($column, DIFFERENT, $expected);
			}
			
			/**
			 * Begin querying the model.
			 *
			 * @return Query
			 */
			private static function query() : Query
			{
				
				return (new static)->builder();
			}
			
			/**
			 *
			 * @throws DependencyException
			 * @throws NotFoundException
			 * @return Query
			 *
			 */
			private function builder() : Query
			{
				
				return Query::from($this->table, $this->routes)->primary($this->primary);
			}
			
		}
	}


