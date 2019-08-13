<?php

	namespace Imperium\Query
	{

        use DI\DependencyException;
        use DI\NotFoundException;
		use Imperium\Collection\Collect;
		use Imperium\Connexion\Connect;
		use Imperium\Exception\Kedavra;
		use Imperium\Tables\Table;
		use Imperium\Zen;
        use PDO;

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
			private $pdo_mode = PDO::FETCH_OBJ;

			/**
             * @var string
             */
            private $offset;

            /**
             *
             * The primary key
             *
             * @var string
             *
             */
            private $primary;

            /**
             *
             * The constructor
             *
             * @method __construct
             *
             * @param bool $routes
             * @throws DependencyException
             * @throws NotFoundException
             */
			public function __construct(bool $routes = false)
			{
                $this->connexion =  $routes ? connect(SQLITE,CORE . DIRECTORY_SEPARATOR . 'Routes' . DIRECTORY_SEPARATOR . 'routes.sqlite3') : $this->app(Connect::class);
			}


            /**
             *
             *
             * @return Connect
             *
             */
			public function connexion(): Connect
            {
                return $this->connexion;
            }


            /**
             *
             * List all columns inside the table
             *
             * @return array
             *
             * @throws Kedavra
             *
             */
            public function columns(): array
            {
                $fields = collect();
                switch ($this->connexion()->driver())
                {

                    case MYSQL:
                        foreach ($this->connexion()->set(PDO::FETCH_OBJ)->request("SHOW FULL COLUMNS FROM {$this->table}") as $column)
                            $fields->push($column->Field);
                    break;
                    case POSTGRESQL:
                        foreach ($this->connexion()->set(PDO::FETCH_OBJ)->request("SELECT column_name FROM information_schema.columns WHERE table_name ='{$this->table}'") as $column)
                            $fields->push($column->column_name);
                    break;
                    case SQLITE:
                        foreach ($this->connexion()->set(PDO::FETCH_OBJ)->request("PRAGMA table_info({$this->table})") as $column)
                            $fields->push($column->name);
                    break;
                }
                return $fields->all();
            }

            /**
             *
             *
             * @param string $table
             *
             * @param bool $routes
             * @return Query
             *
             * @throws DependencyException
             * @throws NotFoundException
             */
			public static function from(string $table,bool $routes = false): Query
            {
                return (new static($routes))->select_table($table);
            }

            /**
             *
             * Add a limit
             *
             * @param int $limit
             *
             * @return Query
             *
             */
			public function take(int $limit): Query
			{
				$this->limit = "LIMIT $limit";

				return $this;
			}

            /**
             *
             * Configure limit offset
             *
             * @param int $offset
             *
             * @return Query
             *
             */
			public function offset(int $offset): Query
			{
			    $offset = max(0,$offset);

				$this->offset =  "OFFSET $offset";

				return $this;
			}

            /**
             * @param string $key
             *
             * @return Query
             */
			public function primary(string $key = 'id'): Query
            {
                $this->primary = $key;

                return $this;
            }
			
			/**
			 * @return string
			 *
			 * @throws Kedavra
			 *
			 */
            public function key()
            {
                return def($this->primary) ? $this->primary : $this->primary_key();
            }
			
			
			/**
			 *
			 * Found the primary key
			 *
			 * @return string
			 *
			 *
			 * @throws Kedavra
			 *
			 */
			public function primary_key(): string
			{
				switch ($this->connexion()->driver())
				{
					case MYSQL:
						
						foreach ($this->connexion()->request("show columns from {$this->table} where `Key` = 'PRI';") as $key)
							return $key->Field;
						break;
					
					case POSTGRESQL:
						
						foreach($this->connexion()->request ("select column_name FROM information_schema.key_column_usage WHERE table_name = '{$this->table}';") as $key)
							return $key->column_name;
						break;
					
					case SQLITE:
						
						foreach ($this->connexion()->request("PRAGMA table_info({$this->table})") as $field)
						{
							if (def($field->pk))
								return $field->name;
						}
						break;
				}
				
				throw  new Kedavra('We have not found a primary key');
			}

            /**
             *
             * @param int $id
             *
             * @return object
             *
             * @throws Kedavra
             *
             */
            public function find(int $id)
            {
                return $this->where($this->key(),EQUAL,$id)->fetch(true)->all();
            }

            /**
             *
             * Get values was different of expected
             *
             * @param string $column
             * @param $expected
             *
             * @return Query
             *
             * @throws Kedavra
             */
            public function different(string $column,$expected)
            {
                return $this->where($column,DIFFERENT,$expected);
            }


            /**
             *
             * Count all record
             *
             * @return int
             *
             * @throws Kedavra
             *
             */
            public function sum(): int
            {
                return  sum($this->all());
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
            public function destroy(int $id): bool
            {
                return $this->where($this->key(),EQUAL,$id)->mode(DELETE)->delete();
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
                $offset = def($this->offset) ? $this->offset : '';
                $join = def($this->join) ? $this->join : '';
                $union = def($this->union) ? $this->union : '';
                $mode = def($this->mode) ? $this->mode : '';
                $or = def($this->or) ? $this->or : '';
                $and = def($this->and) ? $this->and : '';
                $columns = def($this->columns) ? $this->columns : "*";


                $mode = def($mode) ? $mode : SELECT;

                switch ($mode)
                {
                    case Query::SELECT:
                        return "SELECT $columns $table $where $and $or $order $limit $offset";
                    break;
                    case Query::DELETE :
                        return "DELETE $table $where $and $or";
                    break;
                    case Query::UNION:
                    case Query::UNION_ALL:
                        return "$union $where $and $or $order $limit $offset";
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

                $this->where = is_numeric($expected) ? "WHERE $column $condition $expected" : "WHERE $column $condition {$this->connexion->pdo()->quote($expected)}";

                return $this;

            }


            /**
             *
             * Get the table name
             *
             * @return string
             *
             */
            public function table(): string
            {
                return  $this->table;
            }




            /***
             *
             * Select only column
             *
             * @param string ...$columns
             *
             * @return Query
             */
			public function select(string ...$columns): Query
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
             * @param mixed $begin The begin value
             * @param mixed $last The last value
             *
             * @return Query
             *
             * @throws Kedavra
             */
			public function between(string $column, $begin, $last): Query
			{

			    $begin = $this->connexion()->pdo()->quote($begin);

			    $last = $this->connexion()->pdo()->quote($last);

			    $this->where = "WHERE $column BETWEEN $begin AND $last";

				return $this;
			}


			/**
			 *
			 * Generate an order by clause
			 *
			 * @method by
			 *
			 * @param string $column The column name
			 * @param string $order  The order by option
			 *
			 * @return Query
			 *
			 */
			public function by(string $column, string $order = DESC): Query
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
			public function all()
			{
				return $this->use_fetch ? $this->connexion->set($this->pdo_mode)->fetch($this->sql()) : $this->connexion->set($this->pdo_mode)->request($this->sql());
			}

            /**
             *
             * Disable or enable the fetch
             *
             *
             * @method fetch
             *
             *
             * @param bool $fetch
             * @return Query
             */
			public function fetch(bool $fetch = false): Query
			{
				$this->use_fetch = $fetch;

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
					$this->mode = $mode; 
				else
					throw new Kedavra("The current mode is not valid");

				return $this;
			}
			
			/**
			 *
			 * Change pdo fetch mode
			 * 
			 * @method pdo
			 *
			 * @param  int $mode
			 *
			 * @return Query
			 * 
			 */
			public function pdo(int $mode = 0): Query
			{
				$this->pdo_mode= $mode !== 0 ?  $mode :  PDO::FETCH_OBJ;

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
             * @param string $condition
             * @param string $first_table The first table name
             * @param string $second_table The second table name
             * @param string $first_param The first parameter
             * @param string $second_param The second parameter
             * @param string ...$columns
             *
             * @return Query
             * @throws Kedavra
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
				
				if ($this->connexion()->mysql() || $this->connexion()->postgresql())
				{
					$columns = collect($this->columns())->join();

					$this->where = "WHERE CONCAT($columns) LIKE '%$value%'";

				}else{
                    $fields = collect($this->columns());
                    $end = $fields->last();
                    $columns = '';

                    foreach ($fields->all() as $column)
                    {
                        if (different($column, $end))
                            append($columns, "$column LIKE '%$value%' OR ");
                        else
                            append($columns, "$column LIKE '%$value%'");
                    }

                    $this->where = "WHERE $columns";
                }

                return $this;
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
					$this->and = "AND $column $condition {$this->connexion->pdo()->quote($expected)}"; else
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
					$this->or = "OR $value $condition {$this->connexion->pdo()->quote($expected)}"; else
					$this->or = "OR $value $condition $expected";

				return $this;
			}

            /**
             *
             *
             * @param string $column
             * @param mixed ...$values
             *
             * @return Query
             *
             * @throws Kedavra
             *
             */
			public function not(string $column, ...$values): Query
            {

                if (def($this->where))
                {
                    foreach ($values as $value)
                    append($this->where ," AND $column != {$this->connexion->pdo()->quote($value)}");

                }else{

                    foreach ($values as  $k  => $value)
                    {
                        $k == 0 ?  append($this->where,"WHERE $column != {$this->connexion->pdo()->quote($value)} ") :   append($this->where ," AND $column != {$this->connexion->pdo()->quote($value)}");
                    }
                }

                return $this;
            }

            /**
             *
             *
             * @param string $column
             * @param mixed ...$values
             *
             * @return Query
             *
             * @throws Kedavra
             */
            public function only(string $column, ...$values): Query
            {


                if (def($this->where))
                {
                    foreach ($values as $value)

                        append($this->where ," AND $column = {$this->connexion->pdo()->quote($value)}");

                }else{

                    foreach ($values as  $k  => $value)
                    {
                        $k == 0 ?  append($this->where,"WHERE $column = {$this->connexion->pdo()->quote($value)} ") :   append($this->where ," AND $column = {$this->connexion->pdo()->quote($value)}");
                    }
                }

                return $this;
            }

            /**
             *
             *
             *
             * @param string $table
             *
             * @return Query
             *
             */
            private function select_table(string $table): Query
            {
                $this->from = "FROM $table";

                $this->table = $table;
				
                return $this;
            }
			
			/**
			 * @param  int  $page
			 * @param  int  $limit
			 *
			 * @throws Kedavra
			 * @return Collect
			 */
			public function display(int $page, int $limit): Collect
			{
				return collect($this->all())->display($page,$limit);
			}
			
		}

	}
