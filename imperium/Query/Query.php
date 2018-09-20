<?php
/**
 * fumseck added Query.php to imperium
 * The 09/09/17 at 18:59
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package : imperium
 * @author  : fumseck
 */

namespace Imperium\Query {


    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\Imperium;
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

        const VALID_OPERATORS =  [
            self::EQUAL,self::DIFFERENT,self::INFERIOR,self::INFERIOR_OR_EQUAL,self::SUPERIOR,self::SUPERIOR_OR_EQUAL,self::LIKE
        ];

        const UPDATE = 'UPDATE';

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
         * define name of table
         *
         * @param string $table
         *
         * @return Query
         */
        public function set_current_table_name(string $table): Query
        {
            $this->table = "FROM $table";

            return $this;
        }

        private function notDefine(array $keys)
        {
            return collection($keys)->each('is_null')->not_exist(false);
        }

        private function deleteSpace(string $key): string
        {
            return trim($key);
        }

        private function isNotNull(array $keys)
        {
            return collection($keys)->each('is_null')->not_exist(true);
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
                throw new Exception('Missing the query mode select or delete');


            $mode = $this->mode;

            switch ($mode)
            {
                case Imperium::SELECT:

                    // DEFAULT CLAUSE

                    if ($this->notDefine([$this->table,$this->where,$this->columns,$this->order,$this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->order,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->order,$this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->columns,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->columns, $this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table, $this->columns,$this->order,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->columns,$this->order,$this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->where,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->where,$this->columns,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->where,$this->order ,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->where,$this->order,$this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->where,$this->columns,$this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->where,$this->columns,$this->join,$this->union]))
                        return '';


                    // END DEFAULT CLAUSE

                    // START JOIN CLAUSE

                    if ($this->notDefine([$this->table,$this->where,$this->columns,$this->order,$this->limit,$this->union]))
                        return "{$this->deleteSpace($this->join)}";

                    if ($this->notDefine([$this->table,$this->columns,$this->order,$this->limit,$this->union]) && $this->isNotNull([$this->where]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->table,$this->columns,$this->union]) && $this->isNotNull([$this->where,$this->limit,$this->order]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->columns,$this->limit,$this->union]) && $this->isNotNull([$this->order,$this->where]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->table,$this->columns,$this->order,$this->union]) && $this->isNotNull([$this->where,$this->limit]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->columns,$this->order,$this->limit,$this->union]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->table,$this->where,$this->columns,$this->union]) && $this->isNotNull([$this->order,$this->limit]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->where,$this->columns,$this->limit,$this->union]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->table,$this->where,$this->columns,$this->order,$this->union]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->limit)}";

                    // END JOIN CLAUSE

                    // START UNION CLAUSE

                    if ($this->notDefine([$this->table,$this->where,$this->columns,$this->order,$this->limit,$this->join]))
                        return "{$this->deleteSpace($this->union)}";

                    if ($this->notDefine([$this->table, $this->columns,$this->join]) && $this->isNotNull([$this->where,$this->limit,$this->order]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->columns,$this->limit,$this->join]) && $this->isNotNull([$this->order,$this->where]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->table,$this->columns,$this->order,$this->join]) && $this->isNotNull([$this->limit,$this->where]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->columns,$this->order,$this->limit,$this->join]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->table,$this->where,$this->columns,$this->join]) && $this->isNotNull([$this->order,$this->limit]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->where,$this->columns,$this->join]) && $this->isNotNull([$this->order,$this->limit]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->where,$this->columns,$this->limit,$this->join]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->table,$this->where,$this->columns,$this->order,$this->join]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->limit)}";

                    // END UNION CLAUSE

                    // START TABLE CLAUSE

                    if ($this->notDefine([$this->where,$this->columns,$this->order,$this->limit,$this->join,$this->union]))
                        return "SELECT * {$this->deleteSpace($this->table)}";

                    if ($this->notDefine([$this->columns,$this->order,$this->limit,$this->join,$this->union]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->join,$this->union]) && $this->isNotNull([$this->where,$this->columns,$this->order,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->order,$this->limit,$this->join,$this->union]) && $this->isNotNull([$this->where,$this->columns]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->columns,$this->union]) && $this->isNotNull([$this->join,$this->where,$this->order,$this->limit]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->columns,$this->join]) && $this->isNotNull([$this->union,$this->where,$this->limit,$this->order]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->columns,$this->join,$this->union]) && $this->isNotNull([$this->where,$this->order,$this->limit]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->columns, $this->limit,$this->union]) && $this->isNotNull([$this->join,$this->where]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->columns,$this->limit,$this->join,$this->union]) && $this->isNotNull([$this->order,$this->where]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->columns,$this->limit,$this->join]) && $this->isNotNull([$this->order,$this->where,$this->union]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->columns,$this->limit,$this->union]) && $this->isNotNull([$this->order,$this->where,$this->join]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->columns,$this->order,$this->join]) && $this->isNotNull([$this->limit,$this->union,$this->where]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->columns,$this->order,$this->union]) && $this->isNotNull([$this->limit,$this->join,$this->where]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->columns,$this->order,$this->join,$this->union]) && $this->isNotNull([$this->limit,$this->where]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->columns,$this->order,$this->limit,$this->join,$this->union]) && $this->isNotNull([$this->where]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->columns,$this->order,$this->limit,$this->join]) && $this->isNotNull([$this->where,$this->union]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->columns,$this->order,$this->limit,$this->union]) && $this->isNotNull([$this->join,$this->where]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->where,$this->join,$this->union]) && $this->isNotNull([$this->columns,$this->order,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where ,$this->union]) && $this->isNotNull([$this->columns,$this->order,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where ,$this->join]) && $this->isNotNull([$this->columns,$this->order,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->limit,$this->join,$this->union]) && $this->isNotNull([$this->columns,$this->order]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->where,$this->limit,$this->union]) && $this->isNotNull([$this->columns,$this->order]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->where,$this->limit,$this->join]) && $this->isNotNull([$this->columns,$this->order]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->where,$this->order,$this->join,$this->union]) && $this->isNotNull([$this->columns,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->order,$this->union]) && $this->isNotNull([$this->columns,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->order,$this->join]) && $this->isNotNull([$this->columns,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where, $this->order,$this->limit,$this->join]) && $this->isNotNull([$this->columns]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)}";

                    if ($this->notDefine([$this->where, $this->order,$this->limit,$this->union]) && $this->isNotNull([$this->columns]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)}";

                    if ($this->notDefine([$this->where,$this->order,$this->limit,$this->join,$this->union]))
                        return "SELECT {$this->deleteSpace($this->columns)} {$this->deleteSpace($this->table)}";

                    if ($this->notDefine([$this->where,$this->columns,$this->join]) && $this->isNotNull([$this->union,$this->order,$this->limit]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->columns,$this->union]) && $this->isNotNull([$this->join,$this->order,$this->limit]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->columns,$this->join,$this->union]) && $this->isNotNull([$this->limit,$this->order]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->columns,$this->limit,$this->union]) && $this->isNotNull([$this->order,$this->join]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->where,$this->columns,$this->limit,$this->join]) && $this->isNotNull([$this->order,$this->union]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->where,$this->columns,$this->limit,$this->join,$this->union]) && $this->isNotNull([$this->order]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->where,$this->columns,$this->order,$this->union]) && $this->isNotNull([$this->join,$this->limit]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->columns,$this->order,$this->join]) && $this->isNotNull([$this->limit,$this->union]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->columns,$this->order,$this->join,$this->union]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->columns,$this->order,$this->limit,$this->union]))
                        return "{$this->deleteSpace($this->join)}";

                    if ($this->notDefine([$this->where,$this->columns,$this->order,$this->limit,$this->join]))
                        return "{$this->deleteSpace($this->union)}";

                    // END TABLE CLAUSE

                break;
                case Imperium::DELETE:
                    if (def($this->table) && def($this->where))
                        return "$mode {$this->table} {$this->where}";
                break;

            }
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
            if(def($this->mode))
                different($this->mode,self::DELETE) ? $this->order = "ORDER BY $key $order" : $this->order = '';
            else
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
         * set pdo instance
         *
         * @param Connect $connect
         *
         * @return Query
         */
        public function connect(Connect $connect): Query
        {
            $this->connexion = $connect;

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
            if (!has($mode,Imperium::MODE,true))
                throw new Exception('select or delete mode was not found');
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
            return def($this->table,$this->where,$this->mode) ? $this->connexion->execute( $this->sql()) : false;
        }

        /**
         * join clause
         *
         * @param int    $type
         * @param string $firstTable
         * @param string $secondTable
         * @param string $firstParam
         * @param string $secondParam
         * @param array $columns
         * @param string $condition
         *
         * @return Query
         */
        public function join(int $type, string $firstTable,string $secondTable,string $firstParam ,string $secondParam,array $columns = [], string $condition = '=') : Query
        {
            $columnsDefine = empty($columns) ? false : true;
            $select = join(', ',$columns);
            $mode = empty($this->mode) ? "SELECT" : $this->mode;

            switch ($type)
            {
                case Imperium::INNER_JOIN:
                    if ($columnsDefine)
                        $this->join = "$mode $select FROM $firstTable INNER JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                    else
                        $this->join = "$mode * FROM $firstTable INNER JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                break;
                case Imperium::CROSS_JOIN:
                    if ($columnsDefine)
                        $this->join = "$mode $select FROM $firstTable CROSS JOIN $secondTable";
                    else
                        $this->join = "$mode * FROM $firstTable CROSS JOIN $secondTable";
                break;
                case Imperium::LEFT_JOIN:
                    if ($columnsDefine)
                        $this->join = "$mode $select FROM $firstTable LEFT JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                    else
                        $this->join = "$mode * FROM $firstTable LEFT JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                break;
                case Imperium::RIGHT_JOIN:
                    if ($columnsDefine)
                        $this->join = "$mode $select FROM $firstTable RIGHT JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                    else
                        $this->join = "$mode * FROM $firstTable RIGHT JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                break;
                case Imperium::FULL_JOIN:
                    if ($columnsDefine)
                        $this->join = "$mode $select FROM $firstTable FULL JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                    else
                        $this->join = "$mode * FROM $firstTable FULL JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                break;
                case Imperium::NATURAL_JOIN:
                    if ($columnsDefine)
                        $this->join = "$mode $select FROM $firstTable NATURAL JOIN $secondTable";
                    else
                        $this->join = "$mode * FROM $firstTable NATURAL JOIN $secondTable";
                break;
            }
            return $this;
        }

        /**
         * union clause
         *
         * @param int    $mode
         * @param string $firstTable
         * @param string $secondTable
         * @param array  $firstColumns
         * @param array  $secondColumns
         *
         * @return Query
         */
        public function union(int $mode,string $firstTable,string $secondTable,array $firstColumns,array $secondColumns): Query
        {
            $first = join(', ',$firstColumns);
            $second = join(', ',$secondColumns);

            switch ($mode)
            {
                case Imperium::MODE_UNION:
                    if (empty($firstColumns) && empty($secondColumns))
                        $this->union = "SELECT * FROM $firstTable UNION SELECT * FROM $secondTable";
                    else
                        $this->union = "SELECT $first FROM $firstTable UNION SELECT $second FROM $secondTable";
                break;

                case Imperium::MODE_UNION_ALL:
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