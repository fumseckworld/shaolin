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

namespace Nol\Database\Found {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Nol\Database\Connection\Connect;
    use Nol\Database\Query\Sql;
    use Nol\Exception\Kedavra;
    use Nol\Html\Form\Generator\FormGenerator;
    use Nol\Html\Pagination\Pagination;
    use ReflectionClass;
    use ReflectionException;
    use stdClass;

    /**
     *
     * Represent all values for a search.
     *
     * This package contains all useful methods to search and get content values.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Database\Found\Search
     * @version 12
     *
     **/
    abstract class Search
    {
        /**
         * The table name
         */
        protected static string $table = '';

        /**
         * The table prefix
         */
        protected static string $prefix = 'search';

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
         * Generate the search form.
         *
         * @return string
         *
         */
        abstract public function form(): string;

        /**
         *
         * Get an instance of the form builder.
         *
         * @return FormGenerator
         *
         */
        final public function builder(): FormGenerator
        {
            return new FormGenerator();
        }

        /**
         *
         * Method used to paginate all results inside the table or global.
         *
         * @param stdClass $record The current record.
         * @param bool     $global To check if the search are global in all tables.
         *
         * @return string
         *
         */
        abstract public function each(stdClass $record, bool $global): string;

        /**
         *
         * Search the value and return all results with a pagination.
         *
         * @param mixed   $value        The value to search.
         * @param Connect $connect      The selected database.
         * @param int     $current_page The current page to display.
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         * @throws ReflectionException
         *
         * @return string
         *
         */
        final public function search($value, Connect $connect, int $current_page = 1): string
        {

            $global = not_def(static::$table);

            $records = $global ? $this->global($value, $connect) : $this->from($value, $connect);

            $pagination = new Pagination(
                $current_page,
                static::$limit,
                count($records)
            );

            $content = collect(
                (new Sql())
                    ->from(static::$table)
                    ->for($connect)
                    ->take(static::$limit, (($current_page) - 1) * static::$limit)
                    ->by((new Sql())->from(static::$table)->for($connect)->primary())
                    ->like($value)
                    ->get()
            )->for(function (stdClass $record) use ($global) {
                return $this->each($record, $global);
            })->join('');

            return trim(
                sprintf(
                    '%s%s%s%s%s%s%s',
                    static::$beforeContent,
                    $pagination->found(),
                    $content,
                    static::$afterContent,
                    static::$beforePagination,
                    $pagination->render([static::$prefix, $value]),
                    static::$afterPagination
                )
            );
        }

        /**
         *
         * Search a value in a table.
         *
         * @param mixed   $x       The value to search.
         * @param Connect $connect The select database.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return array
         *
         */
        private function from($x, Connect $connect): array
        {
            return (new Sql())->from(static::$table)->for($connect)->like($x)->get();
        }


        /**
         *
         * Search a value in all tables.
         *
         * @param mixed   $x       The value to search.
         * @param Connect $connect The selected database.
         *
         * @throws NotFoundException
         * @throws ReflectionException
         * @throws DependencyException
         *
         * @return array
         *
         */
        private function global($x, Connect $connect): array
        {
            $data = collect();

            $models_base = app('models-path') . DIRECTORY_SEPARATOR . '*.php';

            $models_dir = app('models-path') . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . '*.php';

            $models = array_merge(files($models_base), files($models_dir));

            foreach ($models as $model) {
                $class = sprintf(
                    '%s\%s\%s',
                    app('app-namespace'),
                    app('models-dirname'),
                    collect(
                        explode(
                            '.',
                            collect(
                                explode(
                                    DIRECTORY_SEPARATOR,
                                    $model
                                )
                            )->last()
                        )
                    )->first()
                );

                $reflection = new ReflectionClass(new $class());

                $data->push($reflection->getMethod('search')->invokeArgs($reflection->newInstance(), [$x, $connect]));
            }
            return $data->all();
        }
    }
}
