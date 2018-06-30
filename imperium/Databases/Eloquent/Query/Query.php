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

namespace Imperium\Databases\Eloquent\Query {


    use Imperium\Databases\Eloquent\Connexion\Connexion;
    use Imperium\Databases\Eloquent\Eloquent;
    use Imperium\Databases\Eloquent\Tables\Table;
    use PDO;



    class Query extends Eloquent implements EloquentQueryBuilder
    {
        /**
         * @var string
         */
        private $table;

        /**
         * @var string
         */
        private $where;

        /**
         * @var string
         */
        private $select;

        /**
         * @var string
         */
        private $order;

        /**
         * @var int
         */
        private $limit;

        /**
         * @var string
         */
        private $join;

        /**
         * @var string
         */
        private $union;

        /**
         * @var PDO
         */
        private $pdo;

        /**
         * @var int
         */
        private $fetch = PDO::FETCH_OBJ;

        /**
         * @var string
         */
        private $mode;

        /**
         * @var string
         */
        private $driver;


        /**
         * start the query builder
         *
         * @return Query
         */
        public static function start(): Query
        {
            return new static();
        }

        /**
         * define name of table
         *
         * @param string $table
         *
         * @return Query
         */
        public function setTable(string $table): Query
        {
            $this->table = "FROM $table";

            return $this;
        }



        private function notDefine(array $keys)
        {
            $values = array();
            foreach ($keys as $key)
                array_push($values,is_null($key));

            return !in_array(false,$values);


        }

        private function deleteSpace(string $key): string
        {
            return trim($key);
        }

        private function isNotNull(array $keys)
        {
            $values = array();
            foreach ($keys as $key)
                array_push($values,is_null($key));

            return !in_array(true,$values);
        }

        public function get(): string
        {
            $mode = empty($this->mode) ? Query::SELECT : Query::DELETE;

            switch ($mode)
            {
                case Query::SELECT:

                    // DEFAULT CLAUSE

                    if ($this->notDefine([$this->table,$this->where,$this->select,$this->order,$this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->order,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->order,$this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->select,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->select, $this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table, $this->select,$this->order,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->select,$this->order,$this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->where,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->where,$this->select,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->where,$this->order ,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->where,$this->order,$this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->where,$this->select,$this->limit,$this->join,$this->union]))
                        return '';

                    if ($this->notDefine([$this->table,$this->where,$this->select,$this->join,$this->union]))
                        return '';


                    // END DEFAULT CLAUSE

                    // START JOIN CLAUSE

                    if ($this->notDefine([$this->table,$this->where,$this->select,$this->order,$this->limit,$this->union]))
                        return "{$this->deleteSpace($this->join)}";

                    if ($this->notDefine([$this->table,$this->select,$this->order,$this->limit,$this->union]) && $this->isNotNull([$this->where]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->table,$this->select,$this->union]) && $this->isNotNull([$this->where,$this->limit,$this->order]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->select,$this->limit,$this->union]) && $this->isNotNull([$this->order,$this->where]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->table,$this->select,$this->order,$this->union]) && $this->isNotNull([$this->where,$this->limit]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->select,$this->order,$this->limit,$this->union]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->table,$this->where,$this->select,$this->union]) && $this->isNotNull([$this->order,$this->limit]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->where,$this->select,$this->limit,$this->union]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->table,$this->where,$this->select,$this->order,$this->union]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->limit)}";

                    // END JOIN CLAUSE

                    // START UNION CLAUSE

                    if ($this->notDefine([$this->table,$this->where,$this->select,$this->order,$this->limit,$this->join]))
                        return "{$this->deleteSpace($this->union)}";

                    if ($this->notDefine([$this->table, $this->select,$this->join]) && $this->isNotNull([$this->where,$this->limit,$this->order]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->select,$this->limit,$this->join]) && $this->isNotNull([$this->order,$this->where]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->table,$this->select,$this->order,$this->join]) && $this->isNotNull([$this->limit,$this->where]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->select,$this->order,$this->limit,$this->join]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->table,$this->where,$this->select,$this->join]) && $this->isNotNull([$this->order,$this->limit]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->where,$this->select,$this->join]) && $this->isNotNull([$this->order,$this->limit]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->table,$this->where,$this->select,$this->limit,$this->join]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->table,$this->where,$this->select,$this->order,$this->join]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->limit)}";

                    // END UNION CLAUSE

                    // START TABLE CLAUSE

                    if ($this->notDefine([$this->where,$this->select,$this->order,$this->limit,$this->join,$this->union]))
                        return "SELECT * {$this->deleteSpace($this->table)}";

                    if ($this->notDefine([$this->select,$this->order,$this->limit,$this->join,$this->union]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->join,$this->union]) && $this->isNotNull([$this->where,$this->select,$this->order,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->order,$this->limit,$this->join,$this->union]) && $this->isNotNull([$this->where,$this->select]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->select,$this->union]) && $this->isNotNull([$this->join,$this->where,$this->order,$this->limit]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->select,$this->join]) && $this->isNotNull([$this->union,$this->where,$this->limit,$this->order]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->select,$this->join,$this->union]) && $this->isNotNull([$this->where,$this->order,$this->limit]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->select, $this->limit,$this->union]) && $this->isNotNull([$this->join,$this->where]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->select,$this->limit,$this->join,$this->union]) && $this->isNotNull([$this->order,$this->where]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->select,$this->limit,$this->join]) && $this->isNotNull([$this->order,$this->where,$this->union]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->select,$this->limit,$this->union]) && $this->isNotNull([$this->order,$this->where,$this->join]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->select,$this->order,$this->join]) && $this->isNotNull([$this->limit,$this->union,$this->where]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->select,$this->order,$this->union]) && $this->isNotNull([$this->limit,$this->join,$this->where]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->select,$this->order,$this->join,$this->union]) && $this->isNotNull([$this->limit,$this->where]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->select,$this->order,$this->limit,$this->join,$this->union]) && $this->isNotNull([$this->where]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->select,$this->order,$this->limit,$this->join]) && $this->isNotNull([$this->where,$this->union]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->select,$this->order,$this->limit,$this->union]) && $this->isNotNull([$this->join,$this->where]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->where)}";

                    if ($this->notDefine([$this->where,$this->join,$this->union]) && $this->isNotNull([$this->select,$this->order,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where ,$this->union]) && $this->isNotNull([$this->select,$this->order,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where ,$this->join]) && $this->isNotNull([$this->select,$this->order,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->limit,$this->join,$this->union]) && $this->isNotNull([$this->select,$this->order]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->where,$this->limit,$this->union]) && $this->isNotNull([$this->select,$this->order]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->where,$this->limit,$this->join]) && $this->isNotNull([$this->select,$this->order]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->where,$this->order,$this->join,$this->union]) && $this->isNotNull([$this->select,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->order,$this->union]) && $this->isNotNull([$this->select,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->order,$this->join]) && $this->isNotNull([$this->select,$this->limit]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where, $this->order,$this->limit,$this->join]) && $this->isNotNull([$this->select]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)}";

                    if ($this->notDefine([$this->where, $this->order,$this->limit,$this->union]) && $this->isNotNull([$this->select]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)}";

                    if ($this->notDefine([$this->where,$this->order,$this->limit,$this->join,$this->union]))
                        return "SELECT {$this->deleteSpace($this->select)} {$this->deleteSpace($this->table)}";

                    if ($this->notDefine([$this->where,$this->select,$this->join]) && $this->isNotNull([$this->union,$this->order,$this->limit]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->select,$this->union]) && $this->isNotNull([$this->join,$this->order,$this->limit]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->select,$this->join,$this->union]) && $this->isNotNull([$this->limit,$this->order]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->select,$this->limit,$this->union]) && $this->isNotNull([$this->order,$this->join]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->where,$this->select,$this->limit,$this->join]) && $this->isNotNull([$this->order,$this->union]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->where,$this->select,$this->limit,$this->join,$this->union]) && $this->isNotNull([$this->order]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->order)}";

                    if ($this->notDefine([$this->where,$this->select,$this->order,$this->union]) && $this->isNotNull([$this->join,$this->limit]))
                        return "{$this->deleteSpace($this->join)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->select,$this->order,$this->join]) && $this->isNotNull([$this->limit,$this->union]))
                        return "{$this->deleteSpace($this->union)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->select,$this->order,$this->join,$this->union]))
                        return "SELECT * {$this->deleteSpace($this->table)} {$this->deleteSpace($this->limit)}";

                    if ($this->notDefine([$this->where,$this->select,$this->order,$this->limit,$this->union]))
                        return "{$this->deleteSpace($this->join)}";

                    if ($this->notDefine([$this->where,$this->select,$this->order,$this->limit,$this->join]))
                        return "{$this->deleteSpace($this->union)}";

                    // END TABLE CLAUSE

                break;
                case Query::DELETE:
                    if (!empty($this->table) && !empty($this->where))
                        return "$mode {$this->table} {$this->where}";
                break;

            }
            return '';
        }


        /**
         * where clause
         *
         * @param string      $param
         * @param string      $condition
         * @param mixed       $expected
         * @param string      $like
         * @param null        $betweenOne
         * @param null        $betweenTwo
         *
         * @return Query
         */
        public function where(string $param, string $condition, $expected,string $like = '',$betweenOne = null,$betweenTwo = null ): Query
        {
            $likeClause = empty($like) ? false : true;
            $betweenClause  = is_null($betweenOne) && is_null($betweenTwo) ? false : true;

            if ($likeClause)
            {
                $this->where = "FROM {$this->table} WHERE $param LIKE '%$like%'";
            }

            if ($betweenClause)
            {
                if (is_string($betweenOne) && is_string($betweenTwo))
                    $this->where = "FROM {$this->table} WHERE $param BETWEEN '$betweenOne' AND '$betweenTwo'";
                else
                    $this->where = "FROM {$this->table} WHERE $param BETWEEN $betweenOne AND $betweenTwo";
            }

            if (!$likeClause && !$betweenClause)
            {
                if (is_string($expected))
                    $this->where = "WHERE $param $condition '$expected'";
                else
                    $this->where = "WHERE $param $condition $expected";
            }

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
        public function orderBy(string $key, string $order = 'DESC'): Query
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
        public function setColumns(array $columns = []): Query
        {
            $this->select = join(', ', $columns);

            return $this;
        }

        /**
         * set pdo instance
         *
         * @param PDO $pdo
         *
         * @return Query
         */
        public function setPdo(PDO $pdo): Query
        {
            $this->pdo = $pdo;

            return $this;
        }

        /**
         * count all record in a table
         *
         * @return int
         */
        public function count(): int
        {
           $query = $this->pdo->prepare("SELECT COUNT(*) $this->table");
           $query->execute();
           return $query->fetchColumn();
        }


        /**
         * get all records in a table
         *
         * @return array
         */
        public function getRecords(): array
        {

            $query = $this->pdo->prepare($this->get());
            $query->execute();
            return $query->fetchAll($this->fetch);
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
         */
        public function setMode(string $mode = Query::SELECT): Query
        {
            if (in_array($mode,Query::MODE,true))
                $this->mode = $mode;

            return $this;
        }

        /**
         * run a delete query
         *
         * @return bool
         */
        public function delete(): bool
        {
            if (!empty($this->table) && !empty($this->where))
            {
                $query =   $this->pdo->prepare("DELETE {$this->table} {$this->where}");
                return $query->execute();
            }
            return false;
        }

        /**
         * execute a statement
         *
         * @param string $statement
         *
         * @return bool
         */
        public function query(string $statement): bool
        {
            $query = $this->pdo->prepare($statement);
            return $query->execute();
        }

        /**
         * execute a statement
         *
         * @param string $statement
         *
         * @return array
         */
        public function request(string $statement): array
        {
            $query = $this->pdo->prepare($statement);
            $query->execute();
            return $query->fetchAll($this->fetch);
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
                case Query::INNER_JOIN:
                    if ($columnsDefine)
                        $this->join = "$mode $select FROM $firstTable INNER JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                    else
                        $this->join = "$mode * FROM $firstTable INNER JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                break;
                case Query::CROSS_JOIN:
                    if ($columnsDefine)
                        $this->join = "$mode $select FROM $firstTable CROSS JOIN $secondTable";
                    else
                        $this->join = "$mode * FROM $firstTable CROSS JOIN $secondTable";
                break;
                case Query::LEFT_JOIN:
                    if ($columnsDefine)
                        $this->join = "$mode $select FROM $firstTable LEFT JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                    else
                        $this->join = "$mode * FROM $firstTable LEFT JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                break;
                case Query::RIGHT_JOIN:
                    if ($columnsDefine)
                        $this->join = "$mode $select FROM $firstTable RIGHT JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                    else
                        $this->join = "$mode * FROM $firstTable RIGHT JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                break;
                case Query::FULL_JOIN:
                    if ($columnsDefine)
                        $this->join = "$mode $select FROM $firstTable FULL JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                    else
                        $this->join = "$mode * FROM $firstTable FULL JOIN $secondTable ON $firstTable.$firstParam $condition $secondTable.$secondParam";
                break;
                case Query::NATURAL_JOIN:
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
                case Query::MODE_UNION:
                    if (empty($firstColumns) && empty($secondColumns))
                        $this->union = "SELECT * FROM $firstTable UNION SELECT * FROM $secondTable";
                    else
                        $this->union = "SELECT $first FROM $firstTable UNION SELECT $second FROM $secondTable";
                break;

                case Query::MODE_UNION_ALL:
                    if (empty($firstColumns) && empty($secondColumns))
                        $this->union = "SELECT * FROM $firstTable UNION ALL SELECT * FROM $secondTable";
                    else
                        $this->union = "SELECT $first FROM $firstTable UNION ALL SELECT $second FROM $secondTable";
                break;

            }

            return $this;
        }

        /**
         * execute the query
         *
         * @return bool
         */
        public function execute(): bool
        {
            return $this->query($this->get());
        }

        /**
         * @param Table $table
         * @param string $like
         *
         * @return Query
         */
        public function like(Table $table,string $like): Query
        {
            if (in_array($this->driver,[Connexion::POSTGRESQL,Connexion::MYSQL]))
            {
                $columns = join(', ', $table->getColumns());

                $this->where = "WHERE CONCAT($columns) LIKE '%$like%'";
            }

            if (in_array($this->driver,[Connexion::SQLITE]))
            {
                $fields = $table->getColumns();
                $end = end($fields);
                $columns = '';

                foreach ($fields as $column)
                {
                    if ($column != $end)
                        $columns .= "$column LIKE '%$like%' OR ";
                    else
                        $columns .= "$column LIKE '%$like%'";
                }

                $this->where = "WHERE  $columns";
            }
            return $this;
        }

        /**
         * set database driver
         *
         * @param string $driver
         *
         * @return \Imperium\Databases\Eloquent\Query\Query
         */
        public function setDriver(string $driver): Query
        {
            $this->driver = $driver;
            return $this;
        }
    }

}