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

namespace Nol\Database\Model {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Nol\Database\Query\Sql;
    use Nol\Exception\Kedavra;
    use Nol\Html\Pagination\Pagination;
    use stdClass;

    /**
     *
     * Represent the core of all models.
     *
     * This package contains all useful methods to manage a table content.
     *
     * It's the parent class of all model class.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Database\Model
     * @version 12
     *
     **/
    abstract class Model extends Sql
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
         * Html code before the content of results.
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
        protected static int $limit = 12;

        /**
         *
         * Method used to paginate all results inside the table.
         *
         * @param stdClass $record The current record.
         *
         * @return string
         *
         */
        abstract public function each(stdClass $record): string;

        /**
         *
         * Search the value inside a table.
         *
         * @param mixed $value        The value to search.
         * @param int   $current_page The current page to display
         *
         * @throws Kedavra
         * @throws NotFoundException
         * @throws DependencyException
         *
         * @return string
         *
         */
        final public function search($value, int $current_page = 1): string
        {
            $data = (new Sql())
                ->from($this->table())
                ->for(app('connect')->env())
                ->like($value)
                ->by((new Sql())->from($this->table())->for(app('connect')->env())->primary())
                ->get();

            $pagination = count($data) >= static::$limit ?
                (new Pagination(
                    $current_page,
                    static::$limit,
                    count($data)
                ))->render([static::$table])
                : '';

            $content = collect(
                (new Sql())
                    ->from(static::$table)
                    ->for(app('connect')->env())
                    ->take(static::$limit, (($current_page) - 1) * static::$limit)
                    ->by((new Sql())->from($this->table())->for(app('connect')->env())->primary())
                    ->like($value)
                    ->get()
            )->for(function (stdClass $record) {
                return $this->each($record);
            })->join('');

            return trim(
                sprintf(
                    '%s%s%s%s%s%s',
                    static::$beforeContent,
                    $content,
                    static::$afterContent,
                    static::$beforePagination,
                    $pagination,
                    static::$afterPagination
                )
            );
        }

        /**
         *
         * Display all result with a pagination.
         *
         * @param int   $current_page The current page to display.
         * @param array $data         The data to paginate.
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         * @return string
         *
         */
        final public function paginate(int $current_page, array $data = []): string
        {

            $pagination = (new Pagination($current_page, static::$limit, count($data)))->render([static::$table]);

            $content = collect(
                (new Sql())
                    ->from($this->table())
                    ->for(app('connect')->env())
                    ->take(static::$limit, (($current_page) - 1) * static::$limit)
                    ->by((new Sql())->from($this->table())->for(app('connect')->env())->primary())
                    ->get()
            )->for(function (stdClass $record) {
                return $this->each($record);
            })->join('');

            return trim(
                sprintf(
                    '%s%s%s%s%s%s',
                    static::$beforeContent,
                    $content,
                    static::$afterContent,
                    static::$beforePagination,
                    $pagination,
                    static::$afterPagination
                )
            );
        }

        /**
         *
         * Find all results different of the expected value.
         *
         * @param string $column       The value column name.
         * @param mixed  $expected     The expected value to be escaped of the results lists.
         * @param int    $current_page The current page to display.
         * @param array  $args         The query arguments.
         *
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @throws DependencyException
         * @return string
         *
         */
        final public function different(string $column, $expected, int $current_page, array $args = []): string
        {
            return $this->paginate(
                $current_page,
                (new Sql())
                    ->from($this->table())
                    ->for(app('connect')->env())
                    ->where($column, '!=', $expected)
                    ->by((new Sql())->from($this->table())->for(app('connect')->env())->primary())
                    ->get($args)
            );
        }

        /**
         *
         * Find a record by this identifier.
         *
         * @param int $id The record identifier.
         *
         *
         * @throws NotFoundException
         * @throws Kedavra
         * @throws DependencyException
         * @return array<StdClass>
         */
        final public function find(int $id): array
        {
            return $this->from($this->table())
                ->for(app('connect')->env())
                ->where($this->from($this->table())
                    ->primary(), '=', $id)->get();
        }

        /**
         *
         * Get the complete table name.
         *
         * @return string
         *
         */
        final public function table(): string
        {
            return trim(sprintf('%s%s', static::$prefix, static::$table));
        }
    }
}
