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

    use ArrayIterator;
    use Countable;
    use IteratorAggregate;

    /**
     *
     * Represent the content of a type of request.
     *
     * This package contains all useful methods to get a request type values.
     *
     * @author Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Http\Parameters\Bag
     * @version 12
     *
     * @property array  $data All request values.
     *
     */
    class Bag implements IteratorAggregate, Countable
    {
        /**
         *
         * Bag constructor.
         *
         * @param array $data
         *
         */
        public function __construct(array $data)
        {
            $this->data = $data;
        }

        /**
         *
         * Get all parameter keys
         *
         * @return array
         *
         **/
        public function keys(): array
        {
            return array_keys($this->data);
        }

        /**
         *
         * Get all parameter values
         *
         * @return array
         *
         **/
        public function values(): array
        {
            return array_values($this->data);
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
         * Set a value for a specific key.
         *
         * @param string $key   The bag key.
         * @param mixed  $value The bag value.
         *
         * @return Bag
         *
         */
        public function set(string $key, $value): Bag
        {
            $this->data[$key] = $value;

            return $this;
        }

        /**
         *
         * Add new content in the parameters.
         *
         * @param array $parameters The parameter to add.
         *
         * @return Bag
         *
         */
        public function add(array $parameters = []): Bag
        {
            $this->data = array_replace($this->data, $parameters);

            return $this;
        }

        /**
         *
         * Check if a key has been define.
         *
         * return true if exist or false on not exist.
         *
         * @param string $key
         *
         * @return boolean
         *
         */
        public function has(string $key): bool
        {
            return array_key_exists($key, $this->data);
        }

        /**
         *
         * Destroy a parameter by this key.
         *
         * Return true if value has been destroyed or false on failure.
         *
         * @param string $key The bag key to remove.
         *
         * @return boolean
         *
         */
        public function destroy(string $key): bool
        {
            if ($this->has($key)) {
                unset($this->data[$key]);
                return !$this->has($key);
            }

            return false;
        }

        /**
         *
         * Return the parameter value converted in an integer.
         *
         * @param string $key The parameter key.
         * @param integer $default The default value.
         *
         * @return integer
         *
         */
        public function int(string $key, int $default = 0): int
        {
            return intval($this->get($key, $default));
        }

        /**
         *
         * Return the parameter value converted to boolean.
         *
         * @param string $key The parameter key.
         * @param boolean $default The default value.
         *
         * @return boolean
         *
         */
        public function bool(string $key, bool $default = false): bool
        {
            return $this->filter($key, $default, FILTER_VALIDATE_BOOLEAN);
        }

        /**
         *
         * Get the digits of a parameter value.
         *
         * @param string $key       The parameter key
         * @param string $default   The parameter default value.
         *
         * @return string
         *
         */
        public function digits(string $key, string $default = ''): string
        {
            return str_replace(['-', '+'], '', $this->filter($key, $default, FILTER_SANITIZE_NUMBER_INT));
        }


        /**
         *
         * Filter a key
         *
         * @param string    $key        The parameter key to filter.
         * @param mixed     $default    The default parameter value.
         * @param integer   $filter     The filter constant.
         * @param array     $options    The filter option.
         *
         * @return mixed
         *
         */
        public function filter(string $key, $default = null, int $filter = FILTER_DEFAULT, array $options = [])
        {
            $value = $this->get($key, $default);

            return filter_var($value, $filter, $options);
        }

        /**
         *
         * Get the alphabetic characters of the parameter value.
         *
         * @param string $key       The parameter key
         * @param string $default   The parameter default value
         *
         * @return string
         *
         */
        public function alpha(string $key, string $default = ''): string
        {
            return preg_replace('/[^[:alpha:]]/', '', $this->get($key, $default));
        }

        /**
         *
         * Get the the alphabetic characters and digits of the parameter value.
         *
         * @param string $key       The parameter key
         * @param string $default   The default parameter value
         *
         * @return string
         *
         */
        public function alnum(string $key, string $default = ''): string
        {
            return preg_replace('/[^[:alnum:]]/', '', $this->get($key, $default));
        }

        /**
         *
         * Return all parameters.
         *
         * @return array
         *
         */
        public function all(): array
        {
            return $this->data;
        }

        /**
         *
         * Returns an iterator for parameters.
         *
         * @return ArrayIterator An ArrayIterator instance.
         *
         */
        public function getIterator(): ArrayIterator
        {
            return new ArrayIterator($this->data);
        }

        /**
         *
         * Returns the number of parameters.
         *
         * @return int
         */
        public function count(): int
        {
            return count($this->data);
        }
    }
}
