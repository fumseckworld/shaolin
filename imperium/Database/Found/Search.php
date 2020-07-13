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

namespace Imperium\Database\Found {
    
    use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\Html\Form\Form;
    use Imperium\Html\Pagination\Pagination;
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
        protected static int $limit = 24;
        
        /**
         *
         * Generate the search form.
         *
         * @return string
         *
         */
        abstract public static function form(): string;
        
        /**
         *
         * Get an instance of the form builder.
         *
         * @return Form
         *
         */
        public static function builder(): Form
        {
            return new Form();
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
        abstract protected static function each(stdClass $record, bool $global): string;
        
        /**
         *
         * Search the value and return all results with a pagination.
         *
         * @param mixed $value        The value to search.
         * @param int   $current_page The current page to display.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws ReflectionException
         *
         * @return string
         *
         */
        public static function search($value, int $current_page = 1): string
        {
            $content = '';
            
            $global = not_def(static::$table);
            
            $records = $global ? static::global($value) : static::from($value);
            
            $pagination = (new Pagination($current_page, static::$limit, count($records)))->render(static::$prefix);
            
            foreach ($records as $record) {
                $content .= static::each($record, $global);
            }
            
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
         * Search a value in a table.
         *
         * @param mixed $x The value to search.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return array
         *
         */
        private static function from($x): array
        {
            return app('sql')->from(static::$table)->like($x)->results();
        }
        
        /**
         *
         * Search a value in all tables.
         *
         * @param mixed $x The value to search.
         *
         * @throws ReflectionException
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return array
         *
         */
        private static function global($x): array
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
                
                $data->push($reflection->getMethod('search')->invokeArgs($reflection->newInstance(), [$x]));
            }
            return $data->all();
        }
    }
}
