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

namespace Imperium\Database\Model {

    use Closure;
    use Imperium\Database\Query\Sql;
    use Imperium\Database\Table\Table;

    /**
     *
     * Represent the core of all models.
     *
     * This package contains all useful methods to manage a table content.
     *
     * It's the parent class of all model class.
     *
     * @author Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Database\Model
     * @version 12
     *
     **/
    class Model extends Sql
    {


        /**
         *
         * Search a value inside a table.
         *
         * @param string    $table The table to analyse.
         * @param mixed     $value The value to search.
         *
         * @return array
         *
         */
        public function search(string $table, $value): array
        {
            if ($this->mysql() || $this->postgresql()) {
                $columns = join(', ', $this->columns($table));

                $where = "WHERE CONCAT($columns) LIKE '%$value%'";
            } else {
                $fields = $this->columns($table);
                $end = end($fields);
                $columns =  '';
                foreach ($fields as $field) {
                    if (strcmp($field, $end) != 0) {
                        $columns .= "$field LIKE '%$value%' OR ";
                    } else {
                        $columns .=  "$field LIKE '%$value%'";
                    }
                }
                $where = "WHERE $columns";
            }

            return $this->get(sprintf('SELECT * FROM %s %s', $table, $where));
        }

        /**
         *
         * Paginate the results for a page.
         *
         * @param Closure $callback The prepare output callback.
         * @param integer $page     The current page.
         * @param integer $limit    The limit per page.
         *
         * @return string
         *
         */
        public function paginate(Closure $callback, int $page, int $limit): string
        {
            return '';
        }

        /**
         *
         * Update a record by this identifier.
         *
         * @param integer $id   The record identifier to update.
         * @param array   $values The new record values.
         *
         * @return boolean
         *
         **/
        public function update(int $id, array $values): bool
        {
            return true;
        }

        /**
         *
         * Create a new record.
         *
         * @param array $values The new record values.
         *
         * @return boolean
         *
         */
        public static function create(array $values): bool
        {
            return true;
        }

        /**
         *
         * Find a record by this identifier.
         *
         * @param string  $table The table name.
         * @param integer $id The identifier.
         *
         * @return array
         *
         */
        public function find(string $table, int $id): array
        {
            return $this->where($this->primary($table), '=', $id)->results();
        }

        /**
         *
         * Get values different of the expected value.
         *
         * @param  string  $column The column name.
         * @param  mixed   $expected The expected value.
         *
         * @return array
         *
         */
        public function different(string $column, $expected): array
        {
            return $this->where($column, '!=', $expected)->results();
        }

        /**
         *
         * Delete a record by this id.
         *
         * @param string $table The table name.
         * @param integer $id The record identifier to delete.
         *
         * @return boolean
         *
         */
        public function destroy(string $table, int $id): bool
        {
            return $this->where($this->primary($table), '=', $id)->delete();
        }
    }
}
