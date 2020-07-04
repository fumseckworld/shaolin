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

    use Imperium\Database\Model\Model;
    use Imperium\Exception\Kedavra;
    use ReflectionClass;
    use ReflectionException;

    /**
     *
     * Represent a value for a search.
     *
     * This package contains all useful methods to get search values.
     *
     * @author Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Database\Found\Search
     * @version 12
     *
     *
     **/
    class Search
    {

        /**
         *
         * Search in all tables
         *
         * @param mixed $x The value to search in all tables.
         *
         * @return array
         *
         * @throws ReflectionException
         *
         */
        public static function global($x): array
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

        /**
         *
         * Search inside the given table.
         *
         * @param string $table The name of the table.
         * @param mixed $x The value to search.
         *
         * @return array
         *
         */
        public static function from(string $table, $x): array
        {
            return app('sql')->from($table)->like($x)->results();
        }
    }
}
