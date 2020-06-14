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

namespace Imperium\Database\Query {

    use Closure;
    use Imperium\Database\Table\Table;

    /**
     *
     * Represent an instance of the query builder.
     *
     * This package contains all useful method to make the query builder.
     *
     * @author Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Database\Query\Sql
     * @version 12
     *
     */
    class Sql
    {

        /**
         *
         * Add a where clause
         *
         * @param string $column The where column.
         * @param string $condition The where condition.
         * @param mixed  $value The expected value.
         *
         * @return Sql
         *
         */
        public function where(string $column, string $condition, $value): Sql
        {
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
            return $this;
        }

        /**
         *
         * Add a or clause
         *
         * @param string $column The or column name.
         * @param string $condition The or condition.
         * @param mixed  $value The expected value.
         *
         * @return Sql
         *
         */
        public function orColumn(string $column, string $condition, $value): Sql
        {
            return $this;
        }

        /**
         *
         * Add on the where clause an and clause
         *
         * @param string $column        The column name.
         * @param string $condition     The condition.
         * @param string $expected      The expected value.
         *
         *
         * @return Sql
         *
         */
        public function andColumn(string $column, string $condition, string $expected): Sql
        {
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
         * @return Sql
         *
         */
        public function not(string $column, ...$values): Sql
        {
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
            return '';
        }

        /**
         *
         * Return the query results
         *
         * @return array
         *
         */
        public function results(): array
        {
            return [];
        }
        /**
         *
         * Generate a join clause.
         *
         * @param string $condition     The join condition.
         * @param string $first_table   The join first table name.
         * @param string $second_table  The join second table name.
         * @param string $first_param   The join first parameter.
         * @param string $second_param  The join second parameter.
         * @param string ...$columns    The columns to use.
         *
         * @return Sql
         *
         */
        public function join(
            string $condition,
            string $first_table,
            string $second_table,
            string $first_param,
            string $second_param,
            string ...$columns
        ): Sql {
            return $this;
        }

        /**
         *
         * Generate an union clause.
         *
         * @param string $first_table   The first table name.
         * @param string $second_table  The second table name.
         * @param string $first_column  The first column name.
         * @param string $second_column The second column name.
         *
         * @return Sql
         *
         */
        public function union(
            string $first_table,
            string $second_table,
            string $first_column,
            string $second_column
        ): Sql {
            return $this;
        }

        /**
         *
         * Generate a like clause
         *
         * @param mixed $value The value to search.
         *
         * @return Sql
         *
         */
        public function like($value): Sql
        {
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
            return $this;
        }
        /**
         * Undocumented function
         *
         * @param string $column The column name.
         * @param mixed  $begin  The begin value.
         * @param mixed  $end    The end value.
         * @return Sql
         */
        public function between(string $column, $begin, $end): Sql
        {
            $pdo = app('connect')->pdo();
            $begin = $pdo->quote($begin);
            $end = $pdo->quote($end);
            $x = "WHERE $column BETWEEN $begin AND $end";

            return $this;
        }

        /**
         *
         * Add a limit
         *
         * @param integer $limit  The limit value.
         * @param integer $offset The limit offset.
         *
         * @return Sql
         *
         */
        public function take(int $limit, int $offset = 0): Sql
        {
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
            return $this;
        }

        /**
         *
         * Delete all records found by the executed query.
         *
         * @return boolean
         *
         */
        public function delete(): bool
        {
            return true;
        }
    }
}
