<?php

	namespace Imperium\Query
	{

		use Imperium\Connexion\Connect;
		use Imperium\Exception\Kedavra;
		use Imperium\Tables\Table;
		use Imperium\Zen;

		/**
		 * Class Query
		 *
		 * @package Imperium\Query
		 *
		 * @author Willy Micieli
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Query extends Zen
		{

			/**
			 *
			 * The query mode
			 *
			 * @var int
			 *
			 */
			private $mode;

			/**
			 *
			 * The join clause
			 *
			 * @var string
			 *
			 */
			private $join;

			/**
			 *
			 * The union clause
			 *
			 * @var string
			 *
			 */
			private $union;

			/**
			 *
			 * All selected columns
			 *
			 * @var string
			 *
			 */
			private $columns;

			/**
			 *
			 * The where clause
			 *
			 * @var string
			 *
			 */
			private $where;

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
			 * The instance of Table
			 *
			 * @var Table
			 */
			private $tables;

			/**
			 * The from clause
			 *
			 * @var string
			 *
			 */
			private $from;

			/**
			 *
			 * The order by clause
			 *
			 * @var string
			 *
			 */
			private $order;

			/**
			 *
			 * The limit clause
			 *
			 * @var string
			 *
			 */
			private $limit;

			/**
			 *
			 * The first table name
			 *
			 * @var string
			 *
			 */
			private $first_table;

			/**
			 *
			 * The second table name
			 *
			 * @var string
			 *
			 */
			private $second_table;

			/**
			 *
			 * The where column name
			 *
			 * @var string
			 */
			private $where_param;

			/**
			 *
			 * The where condition
			 *
			 * @var string
			 *
			 */
			private $where_condition;

			/**
			 *
			 * The where expected value
			 *
			 * @var mixed
			 *
			 */
			private $where_expected;

			/**
			 * @var string
			 */
			private $order_cond;
			/**
			 * @var string
			 */
			private $second_param;
			/**
			 * @var string
			 */
			private $first_param;

			/**
			 * @var string
			 */
			private $order_key;

			/**
			 * The current table
			 *
			 * @var string
			 *
			 */
			private $table;

			/**
			 * @var bool
			 */
			private $use_fetch = false;

			/**
			 * @var string
			 */
			private $and;

			/**
			 * @var string
			 */
			private $or;
			
			/**
			 * @var int
			 */
			private $pdo_mode;
			
			/**
			 *
			 * The constructor
			 *
			 * @method __construct
			 *
			 * @param Table   $table
			 * @param Connect $connect
			 *
			 */
			public function __construct(Table $table, Connect $connect)
			{
				$this->connexion = $connect;
				$this->tables = $table;

			}

			/**
			 *
			 * Generate a from clause
			 *
			 * @method from
			 *
			 * @param string $table The table to manage
			 *
			 * @return Query
			 */
			public function from(string $table): Query
			{
				$this->from = "FROM $table";

				$this->table = $table;

				return $this;
			}

			/**
			 *
			 * Return the query generated
			 *
			 * @method sql
			 *
			 * @throws Kedavra
			 *
			 * @return string
			 *
			 */
			public function sql(): string
			{
				$where = def($this->where) ? $this->where : '';
				$order = def($this->order) ? $this->order : '';
				$table = def($this->from) ? $this->from : '';
				$limit = def($this->limit) ? $this->limit : '';
				$join = def($this->join) ? $this->join : '';
				$union = def($this->union) ? $this->union : '';
				$mode = def($this->mode) ? $this->mode : '';
				$or = def($this->or) ? $this->or : '';
				$and = def($this->and) ? $this->and : '';
				$columns = def($this->columns) ? $this->columns : "*";


				is_true(not_def($mode), true, 'The query mode is missing');

				switch ($mode)
				{
				case Query::SELECT:
					return "SELECT $columns $table $where $and $or $order $limit";
					break;
				case Query::DELETE :
					return "DELETE $table $where $and $or";
					break;
				case Query::UNION:
				case Query::UNION_ALL:
					return "$union $where $and $or $order $limit";
					break;
				case collect(self::JOIN_MODE)->exist($mode) :
					return "$join $order $limit";
					break;

				default:
					throw new Kedavra('The query mode is not define');
					break;
				}
			}


			/**
			 *
			 * Generate a where clause
			 *
			 * @method where
			 *
			 * @param string $column    The column name
			 * @param string $condition The condition
			 * @param mixed  $expected  The expected value
			 *
			 * @throws Kedavra
			 *
			 **@return Query
			 *
			 */
			public function where(string $column, string $condition, $expected): Query
			{
				$condition = html_entity_decode($condition);

				$this->where_param = $column;
				$this->where_condition = $condition;
				$this->where_expected = $expected;

				is_true(not_in(self::VALID_OPERATORS, $condition), true, "The operator is invalid");

				$this->where = is_numeric($expected) ? "WHERE $column $condition $expected" : "WHERE $column $condition {$this->connexion->instance()->quote($expected)}";

				return $this;

			}

			/***
			 *
			 * Select only column
			 *
			 * @param string ...$columns
			 *
			 * @return Query
			 */
			public function only(string ...$columns): Query
			{
				$this->columns = collect($columns)->join(', ');

				return $this;
			}

			/**
			 *
			 * Generate a between clause
			 *
			 * @method between
			 *
			 * @param string $column The column name
			 * @param mixed  $begin  The begin value
			 * @param mixed  $last   The last value
			 *
			 * @return Query
			 *
			 */
			public function between(string $column, $begin, $last): Query
			{
				if (is_string($begin) && is_string($last))
					$this->where = "WHERE $column BETWEEN '$begin' AND '$last'"; else
					$this->where = "WHERE $column BETWEEN $begin AND $last";

				return $this;
			}


			/**
			 *
			 * Generate an order by clause
			 *
			 * @method order_by
			 *
			 * @param string $column The column name
			 * @param string $order  The order by option
			 *
			 * @return Query
			 *
			 */
			public function order_by(string $column, string $order = DESC): Query
			{
				$this->order_cond = $order;
				$this->order_key = $column;
				$this->order = "ORDER BY $column $order";

				return $this;
			}

			/**
			 *
			 * Return the result of the generated query in an array
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
				if (def($this->pdo_mode))
					$this->connexion->set($this->pdo_mode);
				return $this->use_fetch ? $this->connexion->fetch($this->sql()) : $this->connexion->request($this->sql());
			}

			/**
			 *
			 * Use a simple fetch
			 *
			 * @return Query
			 *
			 */
			public function use_fetch(): Query
			{
				$this->use_fetch = true;

				return $this;
			}

			/**
			 *
			 * Generate a limit clause
			 *
			 * @method limit
			 *
			 * @param int $limit  The limit value
			 * @param int $offset The limit offset
			 *
			 * @return Query
			 */
			public function limit(int $limit, int $offset): Query
			{
				$this->limit = "LIMIT $limit OFFSET $offset";

				return $this;
			}


			/**
			 *
			 * Define the query mode
			 *
			 * @method mode
			 *
			 * @param int $mode The mode to use
			 *
			 * @throws Kedavra
			 *
			 * @return Query
			 *
			 */
			public function mode(int $mode): Query
			{

				if (collect(Table::MODE)->has($mode))
					$this->mode = $mode; else
					throw new Kedavra("The current mode is not valid");

				return $this;
			}
			
			public function pdo(int $mode)
			{
				$this->pdo_mode = $mode;
				return $this;
			}

			/**
			 *
			 * Run a delete query by a where clause
			 *
			 * @method delete
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function delete(): bool
			{
				is_false(def($this->from, $this->where, $this->mode), true, "We have not found a where clause");

				return $this->connexion->execute($this->sql());
			}

			/**
			 *
			 * Generate a join clause
			 *
			 * @method join
			 *
			 * @param string   $condition
			 * @param string   $first_table  The first table name
			 * @param string   $second_table The second table name
			 * @param string   $first_param  The first parameter
			 * @param string   $second_param The second parameter
			 * @param string[] $columns      The columns
			 *
			 * @throws Kedavra
			 *
			 * @return Query
			 *
			 */
			public function join(string $condition, string $first_table, string $second_table, string $first_param, string $second_param, string ...$columns): Query
			{
				$this->first_table = $first_table;
				$this->second_table = $second_table;
				$this->first_param = $first_param;
				$this->second_param = $second_param;
				$columns_define = def($columns);
				$mode = $this->mode;

				$select = '';

				if ($columns_define)
				{
					$end = collect($columns)->last();
					foreach ($columns as $column)
						different($column, $end) ? append($select, "$first_table.$column, $second_table.$column, ") : append($select, "$first_table.$column, $second_table.$column");
				}

				$mod = 'SELECT';
				$select = $columns_define ? $select : '*';

				switch ($mode)
				{
				case INNER_JOIN:
					$this->join = "$mod $select FROM $first_table INNER JOIN $second_table ON $first_table.$first_param $condition $second_table.$second_param";
					break;
				case CROSS_JOIN:
					$this->join = "$mod $select FROM $first_table CROSS JOIN $second_table";
					break;
				case LEFT_JOIN:
					$this->join = "$mod $select FROM $first_table LEFT JOIN $second_table ON $first_table.$first_param $condition $second_table.$second_param";
					break;
				case RIGHT_JOIN:
					$this->join = "$mod $select FROM $first_table RIGHT JOIN $second_table ON $first_table.$first_param $condition $second_table.$second_param";
					break;
				case FULL_JOIN:
					$this->join = "$mod $select FROM $first_table FULL  JOIN $second_table ON $first_table.$first_param $condition $second_table.$second_param";
					break;
				}


				return $this;
			}

			/**
			 *
			 * Generate an union clause
			 *
			 * @method union
			 *
			 * @param string $first_table   The first table name
			 * @param string $second_table  The second table name
			 * @param string $first_column  The first columns
			 * @param string $second_column The seconds columns
			 *
			 * @return Query
			 *
			 */
			public function union(string $first_table, string $second_table, string $first_column, string $second_column): Query
			{


				switch ($this->mode)
				{
				case Query::UNION:
					if (not_def($first_column, $second_column))
						$this->union = "SELECT * FROM $first_table UNION SELECT * FROM $second_table"; else
						$this->union = "SELECT $first_column FROM $first_table UNION SELECT $second_column FROM $second_table";
					break;
				case Query::UNION_ALL:
					if (not_def($first_column, $second_column))
						$this->union = "SELECT * FROM $first_table UNION ALL SELECT * FROM $second_table"; else
						$this->union = "SELECT $first_column FROM $first_table UNION ALL SELECT $second_column FROM $second_table";
					break;

				}

				return $this;
			}


			/**
			 *
			 * Generate a like clause
			 *
			 * @method like
			 *
			 * @param string $value [description]
			 *
			 * @throws Kedavra
			 *
			 * @return Query
			 *
			 */
			public function like(string $value): Query
			{
				$driver = $this->connexion->driver();

				if (has($driver, [POSTGRESQL, MYSQL]))
				{
					$columns = $this->tables->column()->for($this->table())->columns_to_string();

					$this->where = "WHERE CONCAT($columns) LIKE '%$value%'";
				}

				if (has($driver, [SQLITE]))
				{
					$fields = collect($this->tables->column()->for($this->table())->show());
					$end = $fields->last();
					$columns = '';

					foreach ($fields->all() as $column)
					{
						if (different($column, $end))
							append($columns, "$column LIKE '%$value%' OR "); else
							append($columns, "$column LIKE '%$value%'");
					}

					$this->where = "WHERE $columns";
				}
				return $this;
			}

			/**
			 *
			 * Get the current table
			 *
			 * @return string
			 *
			 */
			private function table(): string
			{
				return $this->table;
			}

			/**
			 *
			 * Add on the where clause an and clause
			 *
			 * @param string $column
			 * @param string $condition
			 * @param string $expected
			 *
			 * @throws Kedavra
			 * @return Query
			 *
			 */
			public function and(string $column, string $condition, string $expected): Query
			{
				if (is_string($expected))
					$this->and = "AND $column $condition {$this->connexion->instance()->quote($expected)}"; else
					$this->and = "AND $column $condition $expected";

				return $this;
			}

			/**
			 *
			 * Add on the where clause a n or clause
			 *
			 * @param string $value
			 * @param string $condition
			 * @param string $expected
			 *
			 * @throws Kedavra
			 *
			 * @return Query
			 *
			 */
			public function or(string $value, string $condition, string $expected): Query
			{
				if (is_string($expected))
					$this->or = "OR $value $condition {$this->connexion->instance()->quote($expected)}"; else
					$this->or = "OR $value $condition $expected";

				return $this;
			}
		}

	}
