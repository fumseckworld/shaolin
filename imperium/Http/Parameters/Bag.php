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

declare(strict_types=1);

namespace Imperium\Http\Parameters {
    /**
     *
     * Represent the content of a type of request.
     *
     * This package contains all useful methods to get a request type values.
     *
     * @author Willy Micieli <fumseckworld@fumseck.eu>
     * @package Imperium\Http\Parameters\Bag
     * @version 12
     *
     * @property array  $data All request values.
     *
     */
    class Bag
    {


        /**
         *
         * Bag constructor.
         *
         * @param array<mixed> $data
         *
         */
        public function __construct(array $data)
        {
            $this->data = $data;
        }
        /**
         *
         * Get the value of the key if exist.
         *
         * Return the value on success or false on failure.
         *
         * @param string $key The value key.
         * @param mixed  $default The value if not define.
         *
         * @return mixed
         *
         */
        public function get(string $key, $default = null)
        {
            return array_key_exists($key, $this->data) ? $this->data[$key] : $default;
        }

        /**
         *
         * Return all values inside the container.
         *
         * @return array
         *
         */
        public function all(): array
        {
            return $this->data;
        }
    }
}
