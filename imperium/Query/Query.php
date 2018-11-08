<?php

namespace Imperium\Query {


    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\Tables\Table;

    class Query
    {

        const EQUAL = '=';

        const DIFFERENT = '!=';

        const SUPERIOR = '>';
        
        const INFERIOR = '<';
        
        const SUPERIOR_OR_EQUAL = '>=';
        
        const INFERIOR_OR_EQUAL = '<=';

        const LIKE = 'LIKE';

        const DELETE = 'DELETE';

        const SELECT = 'SELECT';

        const INNER_JOIN = 'INNER_JOIN';

        const CROSS_JOIN = 'CROSS_JOIN';

        const LEFT_JOIN = 'LEFT_JOIN';

        const RIGHT_JOIN = 'RIGHT_JOIN';

        const FULL_JOIN = 'FULL_JOIN';

        const NATURAL_JOIN = 'NATURAL_JOIN';

        const UPDATE = 'UPDATE';

        const UNION = 'UNION';

        const UNION_ALL = 'UNION_ALL';


        const VALID_OPERATORS =  [
            self::EQUAL,self::DIFFERENT,self::INFERIOR,self::INFERIOR_OR_EQUAL,self::SUPERIOR,self::SUPERIOR_OR_EQUAL,self::LIKE
        ];

        const MODE = [
            self::UPDATE,self::SELECT,self::DELETE,self::UNION,self::UNION_ALL,self::INNER_JOIN,self::CROSS_JOIN,self::LEFT_JOIN,self::RIGHT_JOIN,self::FULL_JOIN,self::NATURAL_JOIN
        ];

        const JOIN_MODE = [
            self::INNER_JOIN,self::CROSS_JOIN,self::LEFT_JOIN,self::RIGHT_JOIN,self::FULL_JOIN,self::NATURAL_JOIN
        ];

        /**
         * sql mode
         *
         * @var string
         */
        private $mode;

        /**
         * @var string
         */
        private $join;

        /**
         * @var string
         */
        private $union;


        /**
         * selected columns
         *
         * @var string
         */
        private  $columns;

        /**
         * where clause
         *
         * @var string
         */
        private $where;
        /**
         *
         * @var Connect
         */
        private $connexion;

        /**
         * @var Table
         */
        private $tables;

        /**
         * @var string
         */
        private $table;

        private $order;

        private $limit;

        /**
        * Query constructor
        *
        * @param Table $table
        * @param Connect $connect
        */
        public function __construct(Table $table, Connect $connect)
        {
            $this->connexion = $connect;
            $this->tables = $table;
        }

        /**
         *
         * Define name of table
         *
         * @param string $table
         *
         * @return Query
         *
         */
        public function from(string $table): Query
        {
            $this->table = "FROM $table";

            return $this;
        }

        /**
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function sql(): string
        {
            if (not_def($this->mode))
                throw new Exception('The query mode is not define');

            $where  = def($this->where) ? $this->where : '';
            $order  = def($this->order) ? $this->order : '';
            $table  = def($this->table) ? $this->table : '';
            $limit  = def($this->limit) ? $this->limit : '';
            $join   = def($this->join)  ? $this->join  : '';
            $union  = def($this->union) ? $this->union : '';
            $mode   = def($this->mode)  ? $this->mode  : '';
            $columns = def($this->columns) ? $this->columns : "*";

            if (equal($mode,Query::SELECT))
                return "$mode $columns $table $where $order $limit";

            if (equal($mode,Query::DELETE))
                return "$mode $table $where";

            if (equal($mode,Query::UNION) || equal($mode,Query::UNION_ALL))
                return "$union $order $limit";

            if (collection(self::JOIN_MODE)->exist($mode))
                return "$join $order $limit";



            return '';
        }


        /**
         * where clause
         *
         * @param string $param
         * @param string $condition
         * @param mixed $expected
         *
         * @return Query
         *
         * @throws Exception
         *
         */
        public function where(string $param, string $condition, $expected): Query
        {
            $condition =  html_entity_decode($condition);

            if(not_in(self::VALID_OPERATORS,$condition))
                throw new Exception("The operator is invalid");

            if (is_string($expected))
                $this->where = "WHERE $param $condition '$expected'";
            else
                $this->where = "WHERE $param $condition $expected";


            return $this;

        }

        /**
         *
         * Build a between clause
         *
         * @param string $column
         * @param $begin
         * @param $end
         *
         * @return Query
         *
         */
        public function between(string $column,$begin,$end): Query
        {
            if (is_string($begin) && is_string($end))
                $this->where = "WHERE $column BETWEEN '$begin' AND '$end'";
            else
                $this->where = "WHERE $column BETWEEN $begin AND $end";

            return $this;
        }


        /**
         * define the order by
         *
         * @param string $key
         * @param string $order
         *
         * @return Query
         */
        public function order_by(string $key, string $order = 'DESC'): Query
        {
            $this->order = "ORDER BY $key $order";
            return $this;
        }


        /**
         * select columns
         *
         * @param array $columns
         *
         * @return Query
         */
        public function set_columns(array $columns = []): Query
        {
            $this->columns = collection($columns)->join(', ');

            return $this;
        }

        /**
         * count all record in a table
         *
         * @return int
         *
         * @throws \Exception
         */
        public function count(): int
        {
            return  count($this->connexion->request("SELECT * {$this->table}"));
        }


        /**
         * get all records in a table
         *
         * @return array

         * @throws \Exception
         */
        public function get(): array
        {
            return $this->connexion->request($this->sql());
        }

        /**
         * define a limit
         *
         * @param int $limit
         *
         * @param int $offset
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
         * set mode
         *
         * @param string $mode
         *
         * @return Query
         *
         * @throws Exception
         */
        public function set_query_mode(string $mode): Query
        {
            if (!has($mode,Query::MODE,true))
                throw new Exception("The current mode is not valid");
            else
                $this->mode = $mode;

            return $this;
        }

        /**
         * run a delete query
         *
         * @return bool
         *
         * @throws Exception
         */
        public function delete(): bool
        {
            return def($this->table,$this->where,$this->mode) ? $this->connexion->execute($this->sql()) : false;
        }

        /**
         * join clause
         *
         * @param string $firstTable
         * @param string $secondTable
         * @param string $firstParam
         * @param string $secondParam
         * @param string $condition
         * @param array $columns
         *
         * @return Query
         *
         * @throws Exception
         *
         */
        public function join( string $firstTable,string $secondTable,string $firstParam ,string $secondParam,string $condition = '=',array $columns = []) : Query
        {
            $columnsDefine = def($columns);
            $select = join(', ',$columns);
            $mode = $this->mode;
            switch ($mode)
            {
                case Query::INNER_JOIN:
                    if ($columnsDefine)
                        $this->join = "SELECT $select FROM $firstTable INNER JOIN $secondTable ON $firstTable.$firstParam = $secondTable.$secondParam";
                    else
                        $this->join = "SELECT * FROM $firstTable INNER JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                break;
                case Query::CROSS_JOIN:
                    if ($columnsDefine)
                        $this->join = "SELECT $select FROM $firstTable CROSS JOIN $secondTable";
                    else
                        $this->join = "SELECT * FROM $firstTable CROSS JOIN $secondTable";
                break;
                case Query::LEFT_JOIN:
                    if ($columnsDefine)
                        $this->join = "SELECT $select FROM $firstTable LEFT JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                    else
                        $this->join = "SELECT * FROM $firstTable LEFT JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                break;
                case Query::RIGHT_JOIN:
                    if ($columnsDefine)
                        $this->join = "SELECT $select FROM $firstTable RIGHT JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                    else
                        $this->join = "SELECT * FROM $firstTable RIGHT JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                break;
                case Query::FULL_JOIN:
                    if ($columnsDefine)
                        $this->join = "SELECT $select FROM $firstTable FULL JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                    else
                        $this->join = "SELECT * FROM $firstTable FULL JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                break;
                case Query::NATURAL_JOIN:
                    if ($columnsDefine)
                        $this->join = "SELECT $select FROM $firstTable NATURAL JOIN $secondTable";
                    else
                        $this->join = "SELECT * FROM $firstTable NATURAL JOIN $secondTable";
                break;
            }
            return $this;
        }

        /**
         * union clause
         *
         * @param string $firstTable
         * @param string $secondTable
         * @param array $firstColumns
         * @param array $secondColumns
         *
         * @return Query
         *
         * @throws Exception
         *
         */
        public function union(string $firstTable,string $secondTable,array $firstColumns,array $secondColumns): Query
        {

            $first = join(', ',$firstColumns);
            $second = join(', ',$secondColumns);

            switch ($this->mode)
            {
                case Query::UNION:
                    if (empty($firstColumns) && empty($secondColumns))
                        $this->union = "SELECT * FROM $firstTable UNION SELECT * FROM $secondTable";
                    else
                        $this->union = "SELECT $first FROM $firstTable UNION SELECT $second FROM $secondTable";
                break;

                case Query::UNION_ALL:
                    if (empty($firstColumns) && empty($secondColumns))
                        $this->union = "SELECT * FROM $firstTable UNION ALL SELECT * FROM $secondTable";
                    else
                        $this->union = "SELECT $first FROM $firstTable UNION ALL SELECT $second FROM $secondTable";
                break;

            }

            return $this;
        }


        /**
         * @param Table $table
         * @param string $value
         * @return Query
         * @throws Exception
         */
        public function like(Table $table,string $value): Query
        {

            $driver = $this->connexion->get_driver();

            if (has($driver,[Connect::POSTGRESQL,Connect::MYSQL]))
            {
                $columns = join(', ', $table->get_columns());

                $this->where = "WHERE CONCAT($columns) LIKE '%$value%'";
            }

            if (has($driver,[Connect::SQLITE]))
            {
                $fields = $this->tables->get_columns();
                $end = end($fields);
                $columns = '';

                foreach ($fields as $column)
                {
                    if (different($column , $end))
                        append($columns,"$column LIKE '%$value%' OR ");
                    else
                        append($columns ,"$column LIKE '%$value%'");
                }

                $this->where = "WHERE  $columns";
            }
            return $this;
        }
    }

}