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
    class Search extends Model
    {


        /**
         *
         * Get all results inside the table by the search value.
         *
         * @param mixed  $value The value to search.
         * @param string $table The table name.
         *
         *
         * @throws Kedavra
         *
         * @return array
         *
         **/
        public function found($value, string $table): array
        {
            return $this->search($table, $value);
        }
    }
}
