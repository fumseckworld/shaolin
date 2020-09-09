<?php

/**
 * Copyright (C) <2020>  <Willy Micieli>
 *
 * This program is free software : you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https: //www.gnu.org/licenses/>.
 *
 */

namespace Nol\Database\Query {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Nol\Database\Connection\Connect;
    use Nol\Database\Table\Table;
    use Nol\Exception\Kedavra;
    use PDO;

    /**
     *
     * Represent an instance of the query builder.
     *
     * This package contains all useful method to make the query builder.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Database\Query\Sql
     * @version 12
     *
     * @property string  $limit     The limit clause.
     * @property string  $from      The from clause.
     * @property string  $between   The between clause.
     * @property string  $order     The order clause.
     * @property string  $where     The where clause.
     * @property string  $columns   The selected columns.
     * @property string  $join      The join clause.
     * @property string  $union     The union clause.
     * @property Connect $connect   The connection to the base.
     *
     */
    class Sql
    {

        /**
         *
         * Add a where clause
         *
         * @param string $column    The where column.
         * @param string $condition The where condition.
         * @param mixed  $value     The expected value.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @return Sql
         */
        public function where(string $column, string $condition, $value): Sql
        {
            $condition = html_entity_decode($condition);
            $value = is_numeric($value) ? $value : $this->connect->pdo()->quote($value);
            if (empty($this->where)) {
                $this->where = sprintf('WHERE %s %s %s', $column, $condition, strval($value));
            } else {
                $this->where .= sprintf(' AND WHERE %s %s %s', $column, $condition, strval($value));
            }
            return $this;
        }


        /**
         *
         * Select the table to manage
         *
         * @param string $table The table name.
         *
         * @return Sql
         *
         */
        public function from(string $table): Sql
        {
            $this->from = sprintf('FROM %s', $table);

            return $this;
        }

        /**
         *
         * The base connection to interact.
         *
         * @param Connect $connect The connection to use.
         *
         * @return Sql
         *
         */
        public function for(Connect $connect): Sql
        {
            $this->connect = $connect;
            return $this;
        }

        /**
         *
         * Add a or clause
         *
         * @param string $column    The or column name.
         * @param string $condition The or condition.
         * @param mixed  $value     The expected value.
         *
         * @return Sql
         *
         */
        public function or(string $column, string $condition, $value): Sql
        {
            $this->where .= sprintf(' OR WHERE %s %s %s', $column, html_entity_decode($condition), strval($value));
            return $this;
        }

        /**
         *
         * Add on the where clause an and clause
         *
         * @param string $column    The column name.
         * @param string $condition The condition.
         * @param string $expected  The expected value.
         *
         *
         * @return Sql
         *
         */
        public function and(string $column, string $condition, string $expected): Sql
        {
            $this->where .= sprintf(' AND WHERE %s %s %s', $column, html_entity_decode($condition), $expected);
            return $this;
        }

        /**
         *
         * Generate a where clause where values are different of expected values.
         *
         *
         * @param string $column
         * @param mixed  ...$values
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Sql
         *
         */
        public function not(string $column, ...$values): Sql
        {
            foreach ($values as $value) {
                $this->where($column, '!=', $value);
            }
            return $this;
        }


        /**
         *
         * Return the sql query
         *
         * @return string
         *
         */
        public function sql(): string
        {
            $where = empty($this->where) ? '' : $this->where;
            $order = empty($this->order) ? '' : $this->order;
            $table = empty($this->from) ? '' : $this->from;
            $limit = empty($this->limit) ? '' : $this->limit;
            $join = empty($this->join) ? '' : $this->join;
            $union = empty($this->union) ? '' : $this->union;
            $columns = empty($this->columns) ? '*' : $this->columns;

            if (def($this->join)) {
                return sprintf(
                    'SELECT %s %s %s %s %s',
                    $columns,
                    $table,
                    $join,
                    $order,
                    $limit
                );
            } elseif (def($this->union)) {
                return sprintf(
                    '%s %s %s %s',
                    $union,
                    $where,
                    $order,
                    $limit
                );
            }
            return sprintf(
                'SELECT %s %s %s %s %s',
                $columns,
                $table,
                $where,
                $order,
                $limit
            );
        }

        /**
         * @param array $args
         * @param int   $output
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         * @return array
         */
        public function get(array $args = [], int $output = PDO::FETCH_OBJ): array
        {
            return $this->connect->get($this->sql(), $args, $output);
        }

        /**
         *
         * Generate a join clause.
         *
         * @param string        $type
         * @param string        $condition    The join condition.
         * @param string        $first_table  The join first table name.
         * @param string        $second_table The join second table name.
         * @param string        $first_param  The join first parameter.
         * @param string        $second_param The join second parameter.
         * @param string        $where        The join where clause
         * @param array<string> $columns      The columns to use.
         *
         * @return Sql
         */
        public function join(
            string $type,
            string $condition,
            string $first_table,
            string $second_table,
            string $first_param,
            string $second_param,
            string $where = '',
            array $columns = []
        ): Sql
        {
            $select = '';
            if (def($columns)) {
                foreach ($columns as $column) {
                    $select .= "$first_table.$column, $second_table.$column,";
                }
                $select = rtrim($select, ',');
            } else {
                $select = '*';
            }
            if (strtoupper($type) == 'CROSS JOIN') {
                $join = "$type $select FROM $first_table CROSS JOIN $second_table";
            } else {
                $join = sprintf(
                    '%s %s ON %s.%s %s %s.%s  %s',
                    $type,
                    $first_table,
                    $first_table,
                    $first_param,
                    html_entity_decode($condition),
                    $second_table,
                    $second_param,
                    $where
                );
            }
            if (empty($this->join)) {
                $this->join = $join;
            } else {
                $this->join .= sprintf(' AND %s', $join);
            }
            return $this;
        }

        /**
         *
         * Generate an union clause.
         *
         * @param string $type
         * @param string $first_table   The first table name.
         * @param string $second_table  The second table name.
         * @param string $first_column  The first column name.
         * @param string $second_column The second column name.
         *
         * @return Sql
         */
        public function union(
            string $type,
            string $first_table,
            string $second_table,
            string $first_column,
            string $second_column
        ): Sql
        {
            $first_select = def($first_column) ? $first_column : '*';
            $second_select = def($second_column) ? $second_column : '*';
            $union = sprintf(
                'SELECT %s FROM %s %s %s FROM %s',
                $first_select,
                $first_table,
                $type,
                $second_select,
                $second_table
            );
            if (empty($this->union)) {
                $this->union = $union;
            } else {
                $this->union .= sprintf(' AND %s', $union);
            }
            return $this;
        }

        /**
         *
         * Generate a like clause
         *
         * @param mixed $value The value to search.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return Sql
         *
         */
        public function like($value): Sql
        {
            $value = addslashes($value);

            if ($this->connect->not('sqlite')) {
                $columns = collect((new Table($this->connect))->from($this->from)->columns())->join();
                $this->where = "WHERE CONCAT($columns) LIKE '%$value%'";
            } else {
                $fields = collect((new Table($this->connect))->from($this->from)->columns());
                $end = $fields->last();
                $columns = collect();
                foreach ($fields->all() as $column) {
                    if ($column !== $end) {
                        $columns->push("$column LIKE '%$value%' OR ");
                    } else {
                        $columns->push("$column LIKE '%$value%'");
                    }
                }
                $this->where = sprintf('WHERE %s', $columns->join(' '));
            }
            return $this;
        }

        /**
         *
         * Select the results columns.
         *
         * @param string ...$columns The columns to take.
         *
         * @return Sql
         *
         */
        public function only(string ...$columns): Sql
        {
            if (def($columns)) {
                $this->columns = collect($columns)->join();
            }
            return $this;
        }

        /**
         *
         * Generate a between clause.
         *
         * @param string $column The column name.
         * @param mixed  $begin  The begin value.
         * @param mixed  $end    The end value.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Sql
         *
         */
        public function between(string $column, $begin, $end): Sql
        {
            $pdo = $this->connect->pdo();
            $begin = $pdo->quote($begin);
            $end = $pdo->quote($end);
            $this->between = sprintf('WHERE %s BETWEEN %s AND %s', $column, $begin, $end);

            return $this;
        }

        /**
         *
         * Add a limit
         *
         * @param integer $limit  The limit value.
         * @param integer $offset The limit offset.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Sql
         *
         */
        public function take(int $limit, int $offset = 0): Sql
        {
            $this->limit = $this->connect->mysql() ?
                sprintf('LIMIT %d,%d', $offset, $limit) : sprintf('LIMIT %d OFFSET %d', $limit, $offset);

            return $this;
        }

        /**
         *
         * Generate an order by clause
         *
         * @param string $column The column to order results.
         * @param string $order  The order value.
         *
         * @return Sql
         *
         */
        public function by(string $column, string $order = 'DESC'): Sql
        {
            $this->order = sprintf('ORDER BY %s %s', $column, $order);
            return $this;
        }

        /**
         *
         * Get the sum of results.
         *
         * @param array $args the sql arguments.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         * @return int
         */
        public function sum(array $args = []): int
        {
            return count($this->get($args));
        }

        /**
         *
         * Delete all records found by the executed query.
         *
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         * @return boolean
         */
        public function remove(): bool
        {
            if (def($this->from, $this->where)) {
                return $this->connect->exec(sprintf('DELETE %s %s', $this->from, $this->where));
            }
            return false;
        }

        /**
         *
         *
         * Found the primary key on the table.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return string
         *
         */
        public function primary(): string
        {
            switch ($this->connect->driver()) {
                case MYSQL:
                    foreach (
                        $this->connect->get(
                            sprintf(
                                "show columns %s where `Key` = 'PRI';",
                                $this->from
                            )
                        ) as $key
                    ) {
                        if (def($key->Field)) {
                            return $key->Field;
                        }
                    }
                    break;
                case POSTGRESQL:
                    foreach (
                        $this->connect->get(
                            sprintf(
                                "select column_name FROM information_schema.key_column_usage WHERE table_name = '%s';",
                                $this->from
                            )
                        ) as $key
                    ) {
                        if (def($key->column_name)) {
                            return $key->column_name;
                        }
                    }
                    break;
                case SQLITE:
                    foreach ($this->connect->get(sprintf('PRAGMA table_info(%s)', $this->from)) as $key) {
                        if (def($key->pk)) {
                            return $key->name;
                        }
                    }
                    break;
            }
            throw new Kedavra(_('No primary key has been found'));
        }
    }
}
