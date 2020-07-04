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
    use Imperium\Html\Pagination\Pagination;
    use stdClass;

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
    abstract class Model
    {

        /**
         * The table name
         */
        protected static string $table = '';

        /**
         * The table prefix
         */
        protected static string $prefix = '';

        /**
         *
         * Html code before the content of results.4
         *
         */
        protected static string $beforeContent = '';


        /**
         *
         * Html code after the content of results.
         *
         */
        protected static string $afterContent = '';


        /**
         *
         * Html code before the pagination
         *
         */
        protected static string $beforePagination = '';

        /**
         *
         * Html code after the pagination of results.
         *
         */
        protected static string $afterPagination = '';

        /**
         * The per page number
         */
        protected static int $limit = 24;

        /**
         *
         * Method used to paginate all results inside the table.
         *
         * @param stdClass $record The current record.
         *
         * @return string
         *
         */
        abstract public static function each(stdClass $record): string;


        /**
         *
         * Search a value inside a table.
         *
         * @param mixed     $value The value to search.
         *
         * @return array
         *
         */
        final public static function search($value): array
        {
            $x = app('connect');
            if ($x->mysql() || $x->postgresql()) {
                $columns = join(', ', app('table')->from(static::from())->columns());

                $where = "WHERE CONCAT($columns) LIKE '%$value%'";
            } else {
                $fields = app('table')->from(static::from())->columns();
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

            return $x->get(sprintf('SELECT * FROM %s %s', static::from(), $where));
        }

        /**
         *
         * Paginate the results for a page.
         *
         * @param integer $current_page     The current page.
         *
         * @return string
         *
         */
        final public static function paginate(int $current_page): string
        {
            $content = '';
            $sql = app('sql')->from(static::from());
            $pagination = (new Pagination($current_page, static::$limit, $sql->sum()))->render(static::$table);

            $records =  $sql->take(static::$limit, (($current_page) - 1) * static::$limit)
                ->by($sql->primary())->results();

            foreach ($records as $record) {
                $content .= static::each($record);
            }
            return trim(sprintf(
                '%s%s%s%s%s%s',
                static::$beforeContent,
                $content,
                static::$afterContent,
                static::$beforePagination,
                $pagination,
                static::$afterPagination
            ));
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
        final public static function update(int $id, array $values): bool
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
        final public static function create(array $values): bool
        {
            return true;
        }

        /**
         *
         * Find a record by this identifier.
         *
         * @param integer $id The identifier.
         *
         * @return array
         *
         */
        final public static function find(int $id): array
        {
            $sql = app('sql')->from(static::from());
            return $sql->where($sql->primary(), '=', $id)->results();
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
        final public static function different(string $column, $expected): array
        {
            return app('sql')->where($column, '!=', $expected)->results();
        }

        /**
         *
         * Delete a record by this id.
         *
         * @param integer $id The record identifier to delete.
         *
         * @return boolean
         *
         */
        final public static function destroy(int $id): bool
        {
            $sql = app('sql')->from(static::from());
            return  $sql->where($sql->primary(), '=', $id)->delete();
        }


        /**
         *
         * Count all records in the table
         *
         * @return integer
         *
         */
        final public static function all(): int
        {
            return app('sql')->from(static::from())->sum();
        }

        /**
         *
         * Get the complete table name.
         *
         * @return string
         *
         */
        final private static function from(): string
        {
            return trim(sprintf('%s%s', static::$prefix, static::$table));
        }
    }
}
