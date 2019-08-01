<?php


	namespace Imperium\Model
	{

		use DI\DependencyException;
		use DI\NotFoundException;
		use Imperium\App;
		use Imperium\Connexion\Connect;
		use Imperium\Exception\Kedavra;
		use Imperium\Query\Query;
		use Imperium\Request\Request;
		use Imperium\Routing\Route;
		use Imperium\Security\Csrf\Csrf;
		use Imperium\Tables\Table;
		use Imperium\Collection\Collect;
		use Imperium\Html\Form\Form;
		use Imperium\Zen;
		use PDO;
		use Imperium\Import\Import;

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
		class Model extends Zen
		{


			/**
			 *
			 * The connection to the base
			 *
			 * @var Connect
			 *
			 */
			private $connexion;

			/**
			 *
			 * The table management
			 *
			 * @var Table
			 *
			 */
			private $table;

			/**
			 *
			 * The current table
			 *
			 * @var string
			 *
			 */
			private $current;

			/**
			 *
			 * The queries management instance
			 *
			 * @var Query
			 *
			 */
			private $sql;


			/**
			 *
			 * The column used
			 *
			 * @var string
			 *
			 */
			private $column;

			/**
			 *
			 * The where condition
			 *
			 * @var string
			 *
			 */
			private $condition;

			/**
			 *
			 * The expected value
			 *
			 * @var mixed
			 *
			 */
			private $expected;

			/**
			 *
			 * The selected columns
			 *
			 * @var string
			 *
			 */
			private $only;

			/**
			 *
			 * @var Collect
			 *
			 */
			private $data;
			/**
			 * @var Request
			 */
			private $request;

			/**
			 *
			 *
			 * @method __construct
			 *
			 * @param Connect $connect
			 * @param Table   $table
			 * @param Query   $query
			 * @param Request $request
			 */
			public function __construct(Connect $connect)
			{
				$this->connexion = $connect;

				$this->data = collect();
			}

			
			/**
			 * 
			 * Check the current driver
			 *  
			 * @method check
			 *
			 * @param string $driver
			 *
			 * @return bool
			 * 
			 */
			public function check(string $driver): bool
			{
				return equal($this->connexion->driver(),$driver);
			}

			/**
			 *
			 * Dump a table or the base
			 *
			 * @method dump
			 *
			 * @param string ...$tables
			 *
			 * @throws Kedavra
			 * 
			 * @return bool
			 *
			 */
			public function dump(string ...$tables): bool
			{
				return dumper(false, $tables);
			}

			/**
			 *
			 * Dump the base
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function dump_base(): bool
			{
				return dumper(true);
			}

			/**
			 *
			 * Select a table
			 *
			 * @param string $table
			 *
			 * @return Model
			 */
			public function from(string $table): Model
			{
				$this->current = $table;

				return $this;
			}


			/**
			 *
			 * Return the current table
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			private function current(): string
			{
				is_true(not_def($this->current), true, "No table selected");

				return $this->current;
			}
			
			/**
			 *
			 * Return the primary key
			 *
			 * @method primary
			 *
			 * @throws Kedavra
			 * @return string
			 *
			 */
			public function primary(): string
			{
				return $this->table()->from($this->current())->primary();
			}

			/**
			 *
			 * Find a record by a column
			 *
			 * @param string $column
			 * @param mixed  $expected
			 *
			 * @throws Kedavra
			 *
			 * @return object
			 *
			 */
			public function by(string $column, $expected)
			{
				return $this->query()->from($this->current())->mode(SELECT)->where($column, EQUAL, $expected)->fetch(true)->get();
			}


			/**
			 *
			 * Search in the current table a value
			 *
			 * @method search
			 *
			 * @param string $value       The value to search
			 * @param bool   $json_output To save data in a json file
			 *
			 * @throws Kedavra
			 * @return array|string
			 *
			 */
			public function search(string $value, bool $json_output = false)
			{
				return $json_output ? collect($this->query()->from($this->current())->mode(SELECT)->like($value)->get())->json() : $this->query()->from($this->current())->mode(Query::SELECT)->like($value)->get();
			}

			/**
			 *
			 * Select only the columns
			 *
			 * @method only
			 *
			 * @param string[] $columns The columns name
			 *
			 * @return Model
			 *
			 */
			public function only(string ...$columns): Model
			{
				$this->only = collect($columns)->join();

				return $this;
			}

			/**
			 *
			 * Return the query result
			 *
			 * @method get
			 *
			 * @throws Kedavra
			 *
			 * @return mixed
			 *
			 */
			public function get()
			{
				is_true(not_def($this->column, $this->expected, $this->condition), true, "The where clause was not found");

				return def($this->only) ? $this->query()->from($this->current())->mode(SELECT)->where($this->column, $this->condition, $this->expected)->only($this->only)->get() : $this->query()->from($this->current())->mode(Query::SELECT)->where($this->column, $this->condition, $this->expected)->get();
			}


			/**
			 *
			 * Set a column value
			 *
			 * @method set
			 *
			 * @param string $column_name The column name
			 * @param mixed  $value       The value
			 *
			 * @return Model
			 *
			 */
			public function set(string $column_name, $value): Model
			{
				$this->data->put($column_name, $value);

				return $this;
			}

			/**
			 *
			 * Save new record in the table
			 *
			 * @throws Kedavra
			 * @return bool
			 *
			 */
			public function save(): bool
			{
				$data = collect();

				foreach ($this->columns() as $column)
					$data->put($column, $this->data->get($column));

				return $this->insert_new_record($this, $data->all());
			}

			/**
			 *
			 * Return the sql builder instance
			 *
			 * @return Query
			 *
			 */
			public function query(): Query
			{
				return new Query($this->table(),$this->connexion);
			}

			/**
			 *
			 * @return Table
			 *
			 */
			public function table(): Table
			{
				return new Table($this->connexion);
			}


			/**
			 *
			 * Get the news records with a limit and order by clause
			 *
			 * @param string $order_column
			 * @param int    $limit
			 * @param int    $offset
			 *
			 * @throws Kedavra
			 * @return array
			 *
			 */
			public function news(string $order_column, int $limit, int $offset = 0): array
			{
				return $this->query()->from($this->current())->mode(SELECT)->limit($limit, $offset)->order_by($order_column)->get();
			}

			/**
			 *
			 * Get the lasts record by a limit and an order by clause
			 *
			 * @param string $order_column
			 * @param int    $limit
			 * @param int    $offset
			 *
			 * @throws Kedavra
			 * @return array
			 *
			 */
			public function last(string $order_column, int $limit, int $offset = 0): array
			{
				return $this->query()->from($this->current())->mode(SELECT)->limit($limit, $offset)->order_by($order_column, ASC)->fetch()->get();
			}

			/**
			 *
			 * Get all records in current table
			 * with an order by
			 *
			 * @param string $column
			 * @param string $order
			 *
			 * @throws Kedavra
			 * @return array
			 *
			 */
			public function all(string $column = '', string $order = DESC): array
			{
				return def($column) ? $this->table()->from($this->current())->all($column, $order) : $this->table()->from($this->current())->all($this->primary(), $order);
			}

			/**
			 *
			 * Return a result by a column or fail
			 *
			 * @param string $column
			 * @param        $expected
			 *
			 * @param string $message
			 *
			 * @throws Kedavra
			 * @return object
			 *
			 */
			public function by_or_fail(string $column, $expected, string $message = 'Record was not found'): object
			{
				return exist($this->by($column, $expected), true, $message);
			}

			/**
			 *
			 * Select a record by this id
			 *
			 * @param int $id
			 *
			 * @throws Kedavra
			 * @return object
			 *
			 */
			public function find(int $id)
			{
				return $this->query()->from($this->current())->mode(SELECT)->where($this->primary(), EQUAL, $id)->fetch(true)->get();
			}

			/**object
			 *
			 * Find a record or fail if not found
			 *
			 * @method find_or_fail
			 *
			 * @param int $id The record id
			 *
			 * @throws Kedavra
			 * @return object
			 *
			 */
			public function find_or_fail(int $id)
			{
				return exist($this->find($id),true, 'Record was not found');
			}


			/**
			 *
			 * Select record by a where clause
			 *
			 * @method where
			 *
			 * @param string $column    The column name
			 * @param string $condition The condition
			 * @param mixed  $expected  The expected value
			 *
			 * @return Model
			 *
			 */
			public function where(string $column, string $condition, $expected): Model
			{
				$this->column = $column;

				$this->condition = $condition;

				$this->expected = $expected;

				return $this;
			}


			/**
			 *
			 * Remove a record by this id
			 *
			 * @param int $id
			 *
			 * @throws Kedavra
			 * @return bool
			 *
			 */
			public function remove(int $id): bool
			{
				return $this->query()->from($this->current())->mode(Query::DELETE)->where($this->primary(), EQUAL, $id)->delete();
			}

			/**
			 *
			 * Insert data in the table
			 *
			 * @param $model
			 * @param array $data
			 *
			 * @throws Kedavra
			 * 
			 * @return bool
			 *
			 */
			public function insert_new_record($model, array $data): bool
			{

				return $this->table()->from($this->current())->save($model, $data);
			}

		

			/**
			 *
			 * Return number of record inside the current table
			 *
			 * @param string $table
			 *
			 * @throws Kedavra
			 * 
			 * @return int
			 *
			 */
			public function count(string $table = ''): int
			{
				return def($table) ? $this->table()->from($table)->count() : $this->table()->from($this->current())->count();
			}


			/**
			 *
			 * Escape a string value
			 *
			 * @param string $value
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function quote(string $value): string
			{
				return $this->connexion->instance()->quote($value);
			}


			/**
			 *
			 * Empty the table
			 *
			 * @param string $table
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function truncate(string $table): bool
			{
				return $this->table()->truncate($table);
			}


			/**
			 *
			 * Update a record by this id
			 *
			 * @param int   $id
			 * @param array $data
			 * @param array $ignore
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function update_record(int $id, array $data, array $ignore = []): bool
			{
				return $this->table()->from($this->current())->update($id, $data, $ignore);
			}

			/**
			 * Display all columns inside the current table
			 *
			 * @throws Kedavra
			 * @return array
			 *
			 */
			public function columns(): array
			{
				return $this->table()->column()->for($this->current())->show();
			}

			/**
			 *
			 * Check if the current table has not record
			 *
			 * @param string $table
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function empty(string $table): bool
			{
				return $this->table()->from($table)->is_empty();
			}

			/**
			 * get pdo instance
			 *
			 * @throws Kedavra
			 *
			 * @return PDO
			 *
			 */
			public function pdo(): PDO
			{
				return $this->connexion->instance();
			}

			/**
			 *
			 * Execute a custom query
			 *
			 * @param string $query
			 *
			 * @throws Kedavra
			 * @return bool
			 *
			 */
			public function execute(string $query): bool
			{
				return $this->connexion->execute($query);
			}


		}
	}
