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
		 * @package Imperium\Model
		 *
		 * @author Willy Micieli
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
			 * The number of models to return for pagination.
			 *
			 * @var int
			 */
			protected $per_page = 12;
			
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
			 * Undocumented function
			 *
			 * @return array
			 *
			 * @throws Kedavra
			 *
			 */
			public static function all(): array
			{
				return static::query()->all();
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
			 * @throws Kedavra
			 *
			 */
			public static function create(array $values): bool
			{
				
				$x = collect(static::query()->columns())->join(',');
				
				$values = collect($values)->for('htmlspecialchars')->all();
				
				$table = static::query()->table();
				$id = static::query()->key();
				
				$sql = "INSERT INTO $table ($x) VALUES (";
				
				foreach ($values as $k => $v)
				{
					if (different($k, $id))
					{
						append($sql, static::query()->connexion()->instance()->quote($v) . ', ');
					} else
					{
						if (static::query()->connexion()->mysql() || static::query()->connexion()->sqlite())
							append($sql, 'NULL, ');
						else
							append($sql, "DEFAULT, ");
						
					}
				}
				
				$sql = trim($sql, ', ');
				
				append($sql, ')');
				
				return static::query()->connexion()->execute($sql);
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
			public static function destroy(int $id): bool
			{
				return static::query()->destroy($id);
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
			public static function count(): int
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
			 * @throws Kedavra
			 */
			public static function where(string $column, string $condition, $expected): Query
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
			 * @throws Kedavra
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
			 * @param string $column
			 * @param mixed $expected
			 *
			 * @return Query
			 *
			 * @throws Kedavra
			 *
			 */
			public static function different(string $column, $expected): Query
			{
				return self::where($column, DIFFERENT, $expected);
			}
			
			/**
			 * Begin querying the model.
			 *
			 * @return Query
			 */
			private static function query(): Query
			{
				return (new static)->builder();
				
			}
			
			/**
			 *
			 * @return Query
			 *
			 * @throws DependencyException
			 * @throws NotFoundException
			 */
			private function builder(): Query
			{
				return Query::from($this->table, $this->routes)->primary($this->primary);
				
			}
			
		}
	}


