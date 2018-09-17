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


    use Imperium\Connexion\Connect;
    use Imperium\Databases\Eloquent\Tables\Table;


    interface EloquentQueryBuilder
    {


        /**
         * Query constructor
         *
         * @param Table $table
         * @param Connect $connect
         */
        public function __construct(Table $table, Connect $connect);

        /**
         * set pdo instance
         *
         * @param Connect $connect
         *
         * @return Query
         */
        public function connect(Connect $connect): Query;

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
        public function get(): array;

        /**
         * set mode
         *
         * @param string $mode
         *
         * @return Query
         */
        public function set_query_mode(string $mode): Query;

        /**
         * run a delete query
         *
         * @return bool
         */
        public function delete(): bool;


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
        public function set_current_table_name(string $table): Query;



        /**
         * select columns
         *
         * @param array $columns
         *
         * @return Query
         */
        public function set_columns(array $columns = []): Query;

        /**
         * get the query result
         *
         * @return string
         */
        public function sql(): string;

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
        public function order_by(string $key, string $order = 'desc'): Query;

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



    }
}