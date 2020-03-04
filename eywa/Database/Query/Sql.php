<?php

declare(strict_types=1);

namespace Eywa\Database\Query {

    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Connexion\Connexion;
    use Eywa\Exception\Kedavra;
    use Eywa\Html\Pagination\Pagination;
    use PDO;


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
        private Connect $connexion;

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
        private string $where_expected = '';

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
         *
         * @param Connect $connect
         * @param string $table
         *
         */
        public function __construct(Connect $connect,string $table)
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
         *
         * Create a new record
         *
         * @param array<mixed> $records
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function save(array $records): bool
        {
            $x = collect(collect($this->columns())->del([$this->primary()])->all())->join();

            $sql = "INSERT INTO {$this->table} ($x) VALUES( ";

            foreach ($this->columns() as $column) // modify it
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
         * @return array<mixed>
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
         * @return Connect
         *
         */
        public function connexion() : Connect
        {
            return $this->connexion;
        }

        /**
         *
         * List all columns inside the table
         *
         * @return array<mixed>
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
                case POSTGRESQL:
                    return $this->connexion()->set("SELECT column_name FROM information_schema.columns WHERE table_name ='{$this->table}'")->get(COLUMNS);
                case SQLITE:
                    $x = function ($x){return $x->name ;};
                   return collect($this->connexion()->set("PRAGMA table_info({$this->table})")->get(OBJECTS))->for($x)->all();
                default:
                    return $fields->all();
            }

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
         * Check if a column can be null
         *
         * @param string $column
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function nullable(string $column): bool
        {
            switch ($this->connexion->driver())
            {
                case MYSQL:
                    return $this->connexion->set("SELECT IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS   WHERE table_name = '{$this->table}' AND COLUMN_NAME = '$column' LIMIT 1;")->get(PDO::FETCH_COLUMN)[0] === 'YES';
                case POSTGRESQL:
                    return $this->connexion->set("select is_nullable from information_schema.columns where table_name = '{$this->table}' and column_name = '$column' ")->get(PDO::FETCH_COLUMN)[0] === 'YES';
                default:
                    return false;
            }
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
                case POSTGRESQL:
                    return collect($this->connexion()->set("select column_name FROM information_schema.key_column_usage WHERE table_name = '{$this->table}' and constraint_name ='{$this->table}_pkey';")->get(PDO::FETCH_COLUMN))->first();
                case SQLITE:
                     foreach($this->connexion()->set("PRAGMA table_info({$this->table})")->get(OBJECTS) as $value)
                     {
                         if ($value->pk)
                             return $value->name;

                     }
                 break;
                case SQL_SERVER:
                    return collect($this->connexion()->set("SELECT COLUMN_NAME , CONSTRAINT_NAME FROM INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE WHERE TABLE_NAME = '{$this->table}';")->get(COLUMNS))->first();

            }
            throw  new Kedavra('We have not found a primary key');
        }


        /**
         *
         * Find a record
         *
         * @param int $id
         * @param int $pdo_fetch_mode
         *
         * @return array<mixed>
         *
         * @throws Kedavra
         *
         */
        public function find(int $id,int $pdo_fetch_mode= PDO::FETCH_OBJ): array
        {
            return $this->where($this->primary(), EQUAL, $id)->execute($pdo_fetch_mode);
        }

        /**
         *
         * Throw excecption if not found
         *
         * @param int $id
         * @param int $pdo_fetch_mode
         *
         * @return array<mixed>
         *
         * @throws Kedavra
         */
        public function find_or_fail(int $id,int $pdo_fetch_mode = PDO::FETCH_OBJ): array
        {
            $x = $this->find($id,$pdo_fetch_mode);
            is_false(def($x),true,'The record has not been found');

            return $x;
        }

        /**
         *
         * Get values was different of expected
         *
         * @param  string  $column
         * @param  mixed   $expected
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
         * @return bool
         *
         * @throws Kedavra
         *
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
            $delete = def($this->delete) ? $this->delete : '';

            $columns = def($this->columns) ? $this->columns : "*";

            if (def($union))
                return "$union $where $and $or $order $limit";
            elseif (def($join))
                return "$join $and $or $order $limit";
            elseif(def($delete))
                return "$delete";
            return "SELECT $columns {$this->from} $where $and $or $order $limit ";
        }

        /**
         *
         * Generate a where clause
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

            not_in($this->columns(),$column,true,"The $column column not exist in the {$this->table} table");

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
         * @param array<mixed> $columns
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
         * @param string $column The column name
         * @param mixed $begin The begin value
         * @param mixed $last The last value
         *
         * @return Sql
         *
         * @throws Kedavra
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
         * @param int $pdo_mode
         *
         * @return array<mixed>
         *
         * @throws Kedavra
         *
         */
        public function execute(int $pdo_mode = PDO::FETCH_OBJ): array
        {
            return $this->connexion->set($this->sql())->get($pdo_mode);
        }


        /**
         *
         * Generate a join clause
         *
         * @param string $type
         * @param string $condition
         * @param string $first_table The first table name
         * @param string $second_table The second table name
         * @param string $first_param The first parameter
         * @param string $second_param The second parameter
         * @param array<string> $columns
         *
         * @return Sql
         *
         * @throws Kedavra
         *
         */
        public function join(string $type,string $condition, string $first_table, string $second_table, string $first_param, string $second_param, array $columns) : Sql
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
         * @throws Kedavra
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
         * @param mixed $expected
         *
         * @return Sql
         *
         * @throws Kedavra
         *
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
         * @param array<mixed> $values
         *
         * @return Sql
         *
         * @throws Kedavra
         *
         */
        public function not(string $column, array $values) : Sql
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
         * @param  callable $callable
         * @param  int  $page
         * @param  int  $limit
         *
         * @throws Kedavra
         *
         * @return Sql
         *
         */
        public function paginate(callable $callable, int $page, int $limit) : Sql
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
            return def($this->pagination) ? strval($this->pagination) : '';
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
            return def($this->content) ? strval($this->content) :'';
        }

        /**
         *
         * Select the table
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