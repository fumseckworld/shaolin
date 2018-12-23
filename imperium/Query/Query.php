<?php

namespace Imperium\Query {

    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\Tables\Table;
    use Imperium\Zen;

    /**
    *
    * Management of the queries
    *
    * @author Willy Micieli <micieli@laposte.net>
    *
    * @package imperium
    *
    * @version 4
    *
    * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE
    *
    **/
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
         *
         * The constructor
         *
         * @method __construct
         *
         * @param  Table       $table
         * @param  Connect     $connect
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
         * @param  string $table The table to manage
         *
         * @return Query
         *
         */
        public function from(string $table): Query
        {
            $this->from = "FROM $table";

            return $this;
        }

        /**
         *
         * Return the query generated
         *
         * @method sql
         *
         * @return string
         *
         */
        public function sql(): string
        {
            $where      = def($this->where)     ? $this->where : '';
            $order      = def($this->order)     ? $this->order : '';
            $table      = def($this->from)      ? $this->from  : '';
            $limit      = def($this->limit)     ? $this->limit : '';
            $join       = def($this->join)      ? $this->join  : '';
            $union      = def($this->union)     ? $this->union : '';
            $mode       = def($this->mode)      ? $this->mode  : '';
            $columns    = def($this->columns)   ? $this->columns : "*";


            switch($mode)
            {
                case Query::SELECT:
                    return "SELECT $columns $table $where $order $limit";
                break;
                case Query::DELETE :
                    return "DELETE $table $where";
                break;
                case Query::UNION:
                case Query::UNION_ALL:
                    return "$union $order $limit";
                break;
                case collection(self::JOIN_MODE)->exist($mode) :

                    if(def($this->where_expected,$this->where_param,$this->where_condition))
                    {
                        $where= "WHERE {$this->first_table}.{$this->where_param}  {$this->where_condition} {$this->second_table}.{$this->where_param} ";

                        return "$join $where $order $limit";
                    }
                    return "$join $order $limit";
                break;
                default:
                      throw new Exception('The query mode is not define');
                break;
            }
        }


        /**
         *
         * Generate a where clause
         *
         * @method where
         *
         * @param  string $column    The column name
         * @param  string $condition The condition
         * @param  mixed  $expected  The expected value
         *
         * @return Query
         *
         * @throws Exception
         *
         **/
        public function where(string $column, string $condition, $expected): Query
        {
            $condition =  html_entity_decode($condition);

            $this->where_param = $column;
            $this->where_condition = $condition;
            $this->where_expected = $expected;

            if(not_in(self::VALID_OPERATORS,$condition))
                throw new Exception("The operator is invalid");

            $this->where = is_string($expected) ? "WHERE $column $condition '$expected'" : "WHERE $column $condition $expected";

            return $this;

        }

        /**
         *
         * Generate a between clause
         *
         * @method between
         *
         * @param  string  $column The column name
         * @param  mixed   $begin  The begin value
         * @param  mixed   $last   The last value
         *
         * @return Query
         *
         */
        public function between(string $column,$begin,$last): Query
        {
            if (is_string($begin) && is_string($last))
                $this->where = "WHERE $column BETWEEN '$begin' AND '$last'";
            else
                $this->where = "WHERE $column BETWEEN $begin AND $last";

            return $this;
        }


        /**
         *
         * Gerate an order by clause
         *
         * @method order_by
         *
         * @param  string   $column   The column name
         * @param  string   $order    The order by option
         *
         * @return Query
         *
         */
        public function order_by(string $column, string $order = 'DESC'): Query
        {
            $this->order = "ORDER BY $column $order";

            return $this;
        }


        /**
         *
         * Select columns
         *
         * @method columns
         *
         * @param  array  $columns The columns to use
         *
         * @return Query
         *
         */
        public function columns(array $columns): Query
        {
            $this->columns = collection($columns)->join(', ');

            return $this;
        }

        /**
         *
         * Return the result of the generated query in an array
         *
         * @method get
         *
         * @return array
         *
         */
        public function get(): array
        {
            return $this->connexion->request($this->sql());
        }

        /**
         *
         * Generate a limit clause
         *
         * @method limit
         *
         * @param  int   $limit  The limit value
         * @param  int   $offset  The limit offset
         *
         * @return Query
         *
         */
        public function limit(int $limit,int $offset): Query
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
         * @return Query
         *
         */
        public function mode(int $mode): Query
        {
            if (!has($mode,Query::MODE,true))
                throw new Exception("The current mode is not valid");

            $this->mode = $mode;

            return $this;
        }


        /**
         *
         * Run a delete query by a where clause
         *
         * @method delete
         *
         * @return bool
         *
         */
        public function delete(): bool
        {
            is_false(def($this->from,$this->where,$this->mode),true,"We have not found a where clause");

            return $this->connexion->execute($this->sql());
        }

        /**
         *
         * Generate a join clause
         *
         * @method join
         *
         * @param  string    $first_table   The first table name
         * @param  string    $second_table The second table name
         * @param  string    $first_param   The first parameter
         * @param  string    $second_param The second parameter
         * @param  string[]  $columns      The columns
         *
         * @return Query
         *
         */
        public function join(string $first_table,string $second_table,string $first_param ,string $second_param,string ...$columns) : Query
        {
            $this->first_table = $first_table;
            $this->second_table = $second_table;
            $columns_define = def($columns);
            $mode = $this->mode;
            $condition = Query::EQUAL;
            $select = '';

            if ($columns_define)
            {
                $end = collection($columns)->last();
                foreach($columns as $column)
                    if(different($column,$end))
                        append($select,"$first_table.$column, $second_table.$column, ");
                    else
                        append($select,"$first_table.$column, $second_table.$column");
            }

            $select = $columns_define ? $select : '*';

            switch ($mode)
            {
                case Query::INNER_JOIN:
                    $this->join = "SELECT $select FROM $first_table INNER JOIN $second_table ON $first_table.$first_param $condition $second_table.$second_param";
                break;
                case Query::CROSS_JOIN:
                    $this->join = "SELECT $select FROM $first_table CROSS JOIN $second_table";
                break;
                case Query::LEFT_JOIN:
                        $this->join = "SELECT $select FROM $first_table LEFT JOIN $second_table ON $first_table.$first_param $condition $second_table.$second_param";
                break;
                case Query::RIGHT_JOIN:
                        $this->join = "SELECT $select FROM $first_table RIGHT JOIN $second_table ON $first_table.$first_param $condition $second_table.$second_param";
                break;
                case Query::FULL_JOIN:
                        $this->join = "SELECT $select FROM $first_table FULL  JOIN $second_table ON $first_table.$first_param $condition $second_table.$second_param";                break;
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
         * @param  string $first_table      The first table name
         * @param  string $second_table    The second table name
         * @param  array  $first_columns    The first columns
         * @param  array  $second_columns  The seconds columns
         *
         * @return Query
         *
         */
        public function union(string $first_table,string $second_table,array $first_columns,array $second_columns): Query
        {
            $first   = collection($first_columns)->join(', ');
            $second = collection($second_columns)->join(', ');

            switch ($this->mode)
            {
                case Query::UNION:
                    if (not_def($first_columns,$second_columns))
                        $this->union = "SELECT * FROM $first_table UNION SELECT * FROM $second_table";
                    else
                        $this->union = "SELECT $first FROM $first_table UNION SELECT $second FROM $second_table";
                break;

                case Query::UNION_ALL:
                    if (not_def($first_columns,$second_columns))
                        $this->union = "SELECT * FROM $first_table UNION ALL SELECT * FROM $second_table";
                    else
                        $this->union = "SELECT $first FROM $first_table UNION ALL SELECT $second FROM $second_table";
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
         * @param  string $value [description]
         *
         * @return Query  [description]
         */
        public function like(string $value): Query
        {
            $driver = $this->connexion->driver();

            if (has($driver,[Connect::POSTGRESQL,Connect::MYSQL]))
            {
                $columns = collection($this->tables->columns())->join(', ');

                $this->where = "WHERE CONCAT($columns) LIKE '%$value%'";
            }

            if (has($driver,[Connect::SQLITE]))
            {
                $fields = collection($this->tables->columns());
                $end =  $fields->last();
                $columns = '';

                foreach ($fields->collection() as $column)
                {
                    if (different($column , $end))
                        append($columns,"$column LIKE '%$value%' OR ");
                    else
                        append($columns ,"$column LIKE '%$value%'");
                }

                $this->where = "WHERE $columns";
            }
            return $this;
        }
    }

}
