<?php
/**
 * fumseck added EloquentBuilder.php to imperium
 * The 09/09/17 at 19:01
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


    use Imperium\Databases\Eloquent\Tables\Table;
    use PDO;

    interface EloquentQueryBuilder
    {
        /**
         * start the query builder
         *
         * @return Query
         */
        public static function start(): Query;

        /**
         * set pdo instance
         *
         * @param PDO $pdo
         *
         * @return Query
         */
        public function setPdo(PDO $pdo): Query;

        /**
         * @param Table $table
         * @param string $like
         *
         * @return Query
         */
        public function like(Table $table,string $like): Query;

        /**
         * count all record in a table
         *
         * @return int
         */
        public function count(): int;

        /**
         * get all records in a table
         *
         * @return array
         */
        public function getRecords(): array;

        /**
         * set mode
         *
         * @param string $mode
         *
         * @return Query
         */
        public function setMode(string $mode = Query::SELECT): Query;

        /**
         * run a delete query
         *
         * @return bool
         */
        public function delete(): bool;

        /**
         * execute a statement
         *
         * @param string $statement
         *
         * @return bool
         */
        public function query(string $statement): bool;

        /**
         * execute a statement
         *
         * @param string $statement
         *
         * @return array
         */
        public function request(string $statement): array;

        /**
         * define a limit
         *
         * @param int $limit
         *
         * @param int $offset
         *
         * @return Query
         */
        public function limit(int $limit, int $offset): Query;

        /**
         * define name of table
         *
         * @param string $table
         *
         * @return Query
         */
        public function setTable(string $table): Query;

        /**
         * set database driver
         *
         * @param string $driver
         *
         * @return \Imperium\Databases\Eloquent\Query\Query
         */
        public function setDriver(string $driver): Query;

        /**
         * select columns
         *
         * @param array $columns
         *
         * @return Query
         */
        public function setColumns(array $columns = []): Query;

        /**
         * get the query result
         *
         * @return string
         */
        public function get(): string;

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
        public function where(string $param, string $condition, $expected,string $like = '',$betweenOne = null,$betweenTwo = null ): Query;

        /**
         * define the order by
         *
         * @param string $key
         * @param string $order
         *
         * @return Query
         */
        public function orderBy(string $key, string $order = 'desc'): Query;

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
        public function join(int $type, string $firstTable,string $secondTable,string $firstParam ,string $secondParam,array $columns = [], string $condition = '=') : Query;

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
        public function union(int $mode,string $firstTable,string $secondTable,array $firstColumns,array $secondColumns): Query;

        /**
         * execute the query
         *
         * @return bool
         */
        public function execute(): bool;


    }
}