<?php

declare(strict_types=1);

namespace Eywa\Database\Query {

    use Eywa\Database\Connexion\Connexion;
    use Eywa\Exception\Kedavra;
    use Eywa\Html\Pagination\Pagination;


    class Sql
    {
        const VALID_OPERATORS = [ EQUAL,DIFFERENT,SUPERIOR,INFERIOR,INFERIOR_OR_EQUAL,SUPERIOR_OR_EQUAL  ];


        /**
         *
         * The join clause
         *
         */
        private ?string $join = null;

        /**
         *
         * The union clause
         *
         */
        private ?string $union = null;

        /**
         *
         * All selected columns
         *
         */
        private ?string $columns = null;

        /**
         *
         * The where clause
         *
         */
        private ?string $where = null;

        /**
         *
         * The connection to the base
         *
         */
        private Connexion $connexion;

        /**
         *
         * The from clause
         *
         */
        private string $from;

        /**
         *
         * The order by clause
         *
         */
        private ?string $order = null;

        /**
         *
         * The limit clause
         *
         */
        private ?string $limit = null;

        /**
         *
         * The first table name
         *
         */
        private ?string $first_table = null;

        /**
         *
         * The second table name
         *
         */
        private ?string $second_table = null;

        /**
         *
         * The where column name
         *
         */
        private ?string $where_param = null;

        /**
         *
         * The where condition
         *
         */
        private ?string $where_condition = null;

        /**
         *
         * The where expected value
         *
         */
        private $where_expected = null;

        /**
         *
         * The order condition
         *
         */
        private ?string $order_cond = null;

        /**
         *
         * The second param for union or join
         *
         */
        private ?string $second_param = null;

        /**
         *
         * The first param for union or join
         *
         */
        private ?string $first_param = null;

        /**
         *
         * The order asc or desc
         *
         */
        private ?string $order_key = null;

        /**
         *
         * The current table
         *
         */
        private string $table;


        /**
         *
         * The and clause
         *
         */
        private ?string $and = null;

        /**
         *
         * The or clause
         *
         */
        private ?string $or = null;


        /**
         *
         * The pagination
         *
         */
        private ?string $pagination = null;

        /**
         *
         * The results of selection
         *
         */
        private ?string $content = null;

        /**
         *
         * The delete query
         *
         */
        private ?string $delete = null;

        /**
         *
         * The constructor
         *
         * @method __construct
         *
         * @param Connexion $connect
         * @param string $table
         *
         */
        public function __construct(Connexion $connect,string $table)
        {
            $this->connexion =  $connect;
            $this->from($table);
        }

        /**
         *
         * Check if record exist
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function exist(): bool
        {
            return  def($this->execute());
        }

        /**
         * @param array $records
         * @return bool
         * @throws Kedavra
         */
        public function save(array $records): bool
        {


            $x = collect($this->columns())->join();

            $sql = "INSERT INTO {$this->table} ($x) VALUES( ";
            $id = $this->connexion()->postgresql() ? 'DEFAULT' : 'NULL';
            append($sql,$id,', ');
            foreach ($this->columns() as $column)
            {
                if (array_key_exists($column,$records))
                    append($sql,$this->connexion()->secure($records[$column]),',');
            }

            $sql = trim($sql,',');

            append($sql,')',',');

            $sql = trim($sql,',');
            
            return $this->connexion()->set($sql)->execute();
        }
        /**
         *
         * Get once result
         *
         * @param int $offset
         * @return array
         *
         * @throws Kedavra
         */
        public function once(int $offset = 0): array
        {
            return $this->take(1,$offset)->execute();
        }

        /**
         *
         *
         * @return Connexion
         *
         */
        public function connexion() : Connexion
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
         */
        public function columns() : array
        {
            $fields = collect();

            switch($this->connexion()->driver())
            {
                case MYSQL:
                    return $this->connexion()->set("SHOW FULL COLUMNS FROM {$this->table}")->get(COLUMNS);
                break;
                case POSTGRESQL:
                    return $this->connexion()->set("SELECT column_name FROM information_schema.columns WHERE table_name ='{$this->table}'")->get(COLUMNS);
                break;
                case SQLITE:
                    $x = function ($x){return $x->name ;};
                   return collect($this->connexion()->set("PRAGMA table_info({$this->table})")->get(OBJECTS))->for($x)->all();
                break;
            }

            return $fields->all();
        }


        /**
         *
         * Add a limit
         *
         * @param  int  $limit
         *
         * @param  int  $offset
         *
         * @return Sql
         *
         */
        public function take(int $limit, int $offset = 0) : Sql
        {

            $this->limit = $this->connexion()->mysql() ? "LIMIT $offset,$limit" : "LIMIT $limit OFFSET $offset";

            return $this;
        }


        /**
         *
         * Get the primary key
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function primary(): string
        {
            switch($this->connexion()->driver())
            {
                case MYSQL:
                    return collect($this->connexion()->set("show columns from {$this->table} where `Key` = 'PRI';")->get(COLUMNS))->first();
                    break;
                case POSTGRESQL:
                    return collect($this->connexion()->set("select column_name FROM information_schema.key_column_usage WHERE table_name = '{$this->table}';")->get(COLUMNS))->first();
                    break;
                case SQLITE:
                     foreach($this->connexion()->set("PRAGMA table_info({$this->table})")->get(OBJECTS) as $value)
                        if ($value->pk)
                            return $value->name;
                    break;
                case SQL_SERVER:
                    return collect($this->connexion()->set("SELECT COLUMN_NAME , CONSTRAINT_NAME FROM INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE WHERE TABLE_NAME = '{$this->table}';")->get(COLUMNS))->first();
                break;
            }
            throw  new Kedavra('We have not found a primary key');
        }


        /**
         *
         * @param int $id
         *
         *
         *
         * @return array|object
         *
         * @throws Kedavra
         *
         */
        public function find(int $id)
        {
            return $this->where($this->primary(), EQUAL, $id)->execute();
        }

        /**
         *
         * Get values was different of expected
         *
         * @param  string  $column
         * @param          $expected
         *
         * @throws Kedavra
         *
         * @return Sql
         *
         */
        public function different(string $column, $expected): Sql
        {
            return $this->where($column, DIFFERENT, $expected);
        }

        /**
         *
         * Count all record
         *
         * @throws Kedavra
         *
         * @return int
         *
         */
        public function sum() : int
        {
            return sum($this->execute());
        }

        /**
         *
         * Destroy a record by id
         *
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function destroy() : bool
        {
            $id = $this->primary();

            $result = collect();


            foreach ($this->execute() as $value)
            {
                $x = $value->$id;


                $result->push(static::connexion()->set("DELETE {$this->from} WHERE {$this->primary()} = $x")->execute());
            }


            return $result->ok();
        }

        /**
         *
         * Return the Sql generated
         *
         * @return string
         *
         */
        public function sql() : string
        {

            $where = def($this->where) ? $this->where : '';

            $order = def($this->order) ? $this->order : '';

            $limit = def($this->limit) ? $this->limit : '';

            $join = def($this->join) ? $this->join : '';

            $union = def($this->union) ? $this->union : '';

            $or = def($this->or) ? $this->or : '';

            $and = def($this->and) ? $this->and : '';

            $columns = def($this->columns) ? $this->columns : "*";

            return ((def($this->union) ? "$union $where $and $or $order $limit" : def($this->join)) ? "$join $and $or $order $limit" : def($this->delete)) ? $this->delete : "SELECT $columns {$this->from} $where $and $or $order $limit ";
        }

        /**
         *
         * Generate a where clause
         *
         * @method where
         *
         * @param  string  $column     The column name
         * @param  string  $condition  The condition
         * @param  mixed   $expected   The expected value
         *
         * @throws Kedavra
         *
         **@return Sql
         *
         */
        public function where(string $column, string $condition, $expected) : Sql
        {

            $condition = html_entity_decode($condition);

            $this->where_param = $column;

            $this->where_condition = $condition;

            $this->where_expected = $expected;

            is_true(not_in(self::VALID_OPERATORS, $condition), true, "The operator is invalid");

            $this->where = is_numeric($expected) ? "WHERE $column $condition $expected" : "WHERE $column $condition {$this->connexion->secure($expected)}";

            return $this;
        }

        /**
         *
         * Get the table name
         *
         * @return string
         *
         */
        public function table() : string
        {
            return $this->table;
        }

        /***
         *
         * Select only column
         *
         * @param string ...$columns
         *
         * @return Sql
         *
         */
        public function only(array $columns) : Sql
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
         * @return Sql
         *
         *
         */
        public function between(string $column, $begin, $last) : Sql
        {

            $begin = is_string($begin) ? $this->connexion()->secure($begin) : $begin;

            $last = is_string($last) ? $this->connexion()->secure($last) : $last;

            $this->where = "WHERE $column BETWEEN $begin AND $last";

            return $this;
        }

        /**
         *
         * Generate an order by clause
         *
         * @method by
         *
         * @param  string  $column  The column name
         * @param  string  $order   The order by option
         *
         * @return Sql
         *
         */
        public function by(string $column, string $order = DESC) : Sql
        {

            $this->order_cond = $order;

            $this->order_key = $column;

            $this->order = "ORDER BY $column $order";

            return $this;
        }

        /**
         *
         * Return the result of the generated Sql in an array
         *
         *
         *
         * @param string $class_name
         * @param array $args
         * @return array
         * @throws Kedavra
         */
        public function execute(string $class_name = '',array $args = []): array
        {
            return def($class_name) ? $this->connexion->set($this->sql())->fetch($class_name,$args) : $this->connexion->set($this->sql())->get(OBJECTS);
        }


        /**
         *
         * Generate a join clause
         *
         * @method join
         *
         * @param string $type
         * @param string $condition
         * @param string $first_table The first table name
         * @param string $second_table The second table name
         * @param string $first_param The first parameter
         * @param string $second_param The second parameter
         * @param string ...$columns
         *
         * @return Sql
         *
         * @throws Kedavra
         *
         */
        public function join(string $type,string $condition, string $first_table, string $second_table, string $first_param, string $second_param, string ...$columns) : Sql
        {

            not_in([LEFT_JOIN,RIGHT_JOIN,CROSS_JOIN,NATURAL_JOIN,INNER_JOIN,FULL_JOIN],$type,true,"The type is invalid");

            $this->first_table = $first_table;
            $this->second_table = $second_table;
            $this->first_param = $first_param;
            $this->second_param = $second_param;
            $columns_define = def($columns);

            $select = '';

            if($columns_define)
            {
                $end = collect($columns)->last();

                foreach($columns as $column)
                    different($column, $end) ? append($select, "$first_table.$column, $second_table.$column, ") : append($select, "$first_table.$column, $second_table.$column");
            }

            $select = $columns_define ? $select : '*';

            $this->join = "SELECT $select FROM $first_table $type $second_table ON $first_table.$first_param $condition $second_table.$second_param";

            return $this;
        }

        /**
         *
         * Generate an union clause
         *
         * @method union
         *
         * @param string $type
         * @param string $first_table The first table name
         * @param string $second_table The second table name
         * @param string $first_column The first columns
         * @param string $second_column The seconds columns
         *
         * @return Sql
         *
         * @throws Kedavra
         *
         */
        public function union(string $type,string $first_table, string $second_table, string $first_column, string $second_column) : Sql
        {
            not_in([UNION,UNION_ALL],$type,true,"The type is not valid");

            $this->union = not_def($first_column, $second_column) ?  "SELECT * FROM $first_table $type SELECT * FROM $second_table" : "SELECT $first_column FROM $first_table $type SELECT $second_column FROM $second_table";

            return $this;
        }

        /**
         *
         * Generate a like clause
         *
         * @method like
         *
         * @param  string  $value  [description]
         *
         * @throws Kedavra
         *
         * @return Sql
         *
         */
        public function like(string $value) : Sql
        {

            if($this->connexion()->mysql() || $this->connexion()->postgresql())
            {
                $columns = collect($this->columns())->join();
                $this->where = "WHERE CONCAT($columns) LIKE '%$value%'";
            }
            else
            {
                $fields = collect($this->columns());

                $end = $fields->last();

                $columns = '';

                foreach($fields->all() as $column)
                {
                    if(different($column, $end))
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
         * @return Sql
         *
         */
        public function and(string $column, string $condition, string $expected) : Sql
        {
            append($this->and, " AND $column $condition {$this->connexion->secure($expected)}");

            return $this;
        }

        /**
         *
         * Add on the where clause a n or clause
         *
         * @param string $column
         * @param string $condition
         * @param $expected
         *
         *
         * @return Sql
         */
        public function or(string $column, string $condition, $expected) : Sql
        {

            $expected = is_string($expected) ? $this->connexion()->secure($expected) : $expected;

            append($this->or, " OR $column $condition $expected ");

            return $this;
        }

        /**
         *
         *
         * @param string $column
         * @param mixed ...$values
         *
         *
         * @return Sql
         *
         *
         */
        public function not(string $column, ...$values) : Sql
        {
            $symbol = html_entity_decode(DIFFERENT);

            if(def($this->where))
            {
                foreach($values as $value)
                {
                    $value = is_string($value) ? $this->connexion()->secure($value) : $value;
                    append($this->where, " AND $column $symbol $value");
                }

            }
            else
            {
                foreach($values as $k => $value)
                {
                    $value = is_string($value) ? $this->connexion()->secure($value) : $value;


                    $k == 0 ? append($this->where, "WHERE $column $symbol $value ") : append($this->where, " AND $column $symbol $value");
                }
            }
            return $this;
        }

        /**
         * @param       $callable
         * @param  int  $page
         * @param  int  $limit
         *
         * @throws Kedavra
         *
         * @return Sql
         *
         */
        public function paginate($callable, int $page, int $limit) : Sql
        {

            $this->pagination = (new Pagination($page, $limit, $this->sum()))->paginate();

            $this->content =  collect($this->take($limit, (($page) - 1) * $limit)->by($this->primary())->execute())->for($callable)->join('');

            return  $this;
        }
        /**
         *
         * Return the pagination
         *
         * @return string
         *
         */
        public function pagination(): string
        {
            return $this->pagination;
        }
        /**
         *
         * Return the pagination
         *
         * @return string
         *
         */
        public function content(): string
        {
            return $this->content;
        }

        /**
         *
         *
         *
         * @param  string  $table
         *
         * @return Sql
         *
         */
        public function from(string $table) : Sql
        {

            $this->from = "FROM $table";

            $this->table = $table;

            return $this;
        }
    }
}