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

namespace Imperium\Collection {

    use Closure;
    use Imperium\Exception\Kedavra;
    use Iterator;
    use Opis\Closure\SerializableClosure;

    /**
     *
     * @author Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Collection\Collect
     * @version 12
     *
     * @property array  $data           All data to manage.
     * @property int    $position       The current index position.
     * @property mixed  $beforeValue    The value before a key.
     */
    final class Collect implements Iterator
    {

        /**
         *
         * Collection constructor
         *
         * @param array<mixed> $data The startup data.
         *
         */
        public function __construct(array $data = [])
        {
            $this->data = $data;
            $this->beforeValue = '';
            $this->position();
        }

        /**
         *
         * Creates an array by using one array for keys and another for its values.
         *
         * @param array<mixed> $keys    Array of keys to be used.
         * @param array<mixed> $values  Array of values to be used.
         *
         * @return Collect
         *
         */
        public function combine(array $keys, array $values): Collect
        {
            $x = array_combine($keys, $values);

            return is_bool($x) ? $this : $this->checkout($x);
        }

        /**
         *
         *
         * @return int|mixed|string
         *
         */
        public function firstKey()
        {
            return array_key_first($this->data);
        }

        /**
         *
         *
         * @return int|mixed|string
         *
         */
        public function lastKey()
        {
            return array_key_last($this->data);
        }

        /**
         *
         * Counts all the values of an array.
         *
         * Returns an associative array of values from array as keys and their count as value.
         *
         * @return Collect
         *
         */
        public function count(): Collect
        {
            return $this->checkout(array_count_values($this->all()));
        }

        /**
         *
         * Initialize the position counter.
         *
         * @param int $i The array position index.
         *
         * @return Collect
         *
         */
        public function position(int $i = 0): Collect
        {
            $this->position = $i;

            return $this;
        }

        /**
         *
         * Extract a slice of the array
         *
         * If offset is non-negative, the sequence will start at that offset in the array.
         *
         * If offset is negative, the sequence will start that far from the end of the array.
         *
         * If length is given and is positive, then the sequence will have up to that many elements in it.
         *
         * If the array is shorter than the length, then only the available array elements will be present.
         *
         * If length is given and is negative then the sequence will stop that many elements from the end of the array
         *
         * If it is omitted, then the sequence will have everything from offset up until the end of the array.
         *
         * @param int       $offset The sequence will start at that offset in the array.
         * @param int|null  $length The sequence will have everything from offset up until the end of the array.
         * @param bool      $preserve_keys  Reorder and reset the integer array indices.
         *
         * @return Collect
         *
         */
        public function slice(int $offset, int $length = null, bool $preserve_keys = false): Collect
        {
            return $this->checkout(array_slice($this->all(), $offset, $length, $preserve_keys));
        }

        /**
         *
         *
         * Chunk the array in separate array
         *
         * @param int $x
         *
         * @return Collect
         *
         */
        public function chunk(int $x): Collect
        {
            $data = collect();
            while (def($this->data)) {
                $data->push($this->diff($this->slice($x)->all())->all());

                $this->data = $this->slice($x)->all();
            }
            return $this->checkout($data->all());
        }

        /**
         *
         * Return the array generated.
         *
         * @return array<mixed>
         *
         **/
        public function all(): array
        {
            return $this->data;
        }

        /**
         *
         * Get specifics keys of the array.
         *
         * @param array<mixed> $keys All would like keys.
         *
         * @return Collect
         *
         */
        public function only(...$keys): Collect
        {
            $x = collect();
            foreach ($keys as $key) {
                $x->put($key, $this->get($key));
            }
            return $this->checkout($x->all());
        }

        /**
         *
         * Compare the array with the given array.
         *
         * @param array<mixed> $x The array to compare.
         *
         * @return Collect
         *
         */
        public function diff(array $x): Collect
        {
            return $this->checkout(array_diff($this->all(), $x));
        }

        /**
         *
         * Return the last element of the array.
         *
         * @return mixed
         *
         */
        public function last()
        {
            return end($this->data);
        }

        /**
         *
         * Return the first element of the array.
         *
         * @return mixed
         *
         */
        public function first()
        {
            return reset($this->data);
        }

        /**
         *
         * Return the reverse of the array.
         *
         * @return Collect
         *
         */
        public function reverse(): Collect
        {
            return $this->checkout(array_reverse($this->data));
        }

        /**
         *
         * Return a value before a key
         *
         * @param mixed $key The next value key
         *
         * @return mixed
         *
         */
        public function beforeKey($key)
        {
            if ($this->has($key)) {
                foreach ($this->data as $k => $v) {
                    if ($k !== $key) {
                        $this->beforeValue = $v;
                    } else {
                        return $this->beforeValue;
                    }
                }
            }
            return '';
        }

        /**
         *
         * Collect all values
         *
         * @return Collect
         *
         */
        public function values(): Collect
        {
            return $this->checkout(array_values($this->all()));
        }

        /**
         *
         * Collect all keys
         *
         * @return Collect
         *
         */
        public function keys(): Collect
        {
            return $this->checkout(array_keys($this->all()));
        }

        /**
         *
         * Move pointer to the before position
         * and return the current value
         *
         * @return mixed
         *
         */
        public function before(int $how_many = 1)
        {
            for ($i = 0; $i < $how_many; $i++) {
                $this->position--;
            }

            return $this;
        }

        /**
         *
         * Move pointer to the after position
         * and return the current value
         *
         *
         * @return mixed
         *
         */
        public function after(int $how_many = 1)
        {
            for ($i = 0; $i < $how_many; $i++) {
                $this->position++;
            }
            return $this;
        }

        /**
         *
         * Get a value in the array by a key
         *
         *
         * @param mixed $key
         *
         * @return mixed
         *
         *
         */
        public function get($key)
        {
            if ($this->has($key)) {
                $x = $this->data[$key];

                return $x;
            }
            return null;
        }

        /**
         *
         * Return the max value in the array
         *
         * @return mixed
         *
         */
        public function max()
        {
            return max($this->all());
        }

        /**
         *
         * Get the min value in the array
         *
         *
         * @return mixed
         *
         */
        public function min()
        {
            return min($this->all());
        }

        /**
         *
         * Check if the array has not a key
         *
         * @param mixed $key
         *
         * @return bool
         *
         */
        public function hasNot($key): bool
        {
            return !$this->has($key);
        }

        /**
         * Return the current element
         *
         * @link  https://php.net/manual/en/iterator.current.php
         * @return mixed Can return any type.
         * @since 5.0.0
         */
        public function current()
        {
            return $this->get($this->position);
        }


        /**
         * Return the key of the current element
         *
         * @link  https://php.net/manual/en/iterator.key.php
         * @return mixed scalar on success, or null on failure.
         * @since 5.0.0
         */
        public function key()
        {
            return $this->position;
        }

        /**
         * Checks if current position is valid
         *
         * @link  https://php.net/manual/en/iterator.valid.php
         * @return boolean The return value will be casted to boolean and then evaluated.
         * Returns true on success or false on failure.
         * @since 5.0.0
         */
        public function valid()
        {
            return isset($this->data[$this->position]);
        }

        /**
         * Rewind the Iterator to the first element
         *
         * @link  https://php.net/manual/en/iterator.rewind.php
         * @return void Any returned value is ignored.
         * @since 5.0.0
         */
        public function rewind()
        {
            $this->position = 0;
        }

        /**
         * Move forward to next element
         *
         * @link  https://php.net/manual/en/iterator.next.php
         * @return void Any returned value is ignored.
         * @since 5.0.0
         */
        public function next()
        {
            $this->position++;
        }

        /**
         *
         * Get the next value
         *
         * @return mixed|string
         *
         */
        public function nextValue()
        {
            $this->next();
            return $this->valid() ?  $this->current() : '';
        }
        /**
         *
         * Check if array is empty
         *
         * @return bool
         *
         */
        public function empty()
        {
            return not_def($this->all());
        }

        /**
         *
         * Computes the intersection of arrays
         *
         * @param array<mixed> $x
         *
         * @return Collect
         *
         */
        public function intersect(array $x): Collect
        {
            return $this->checkout(array_intersect($this->all(), $x));
        }

        /**
         *
         * Replaces elements in the array
         *
         *
         * @param array<mixed> ...$data
         *
         * @return Collect
         *
         */
        public function replace(array $data): Collect
        {
            return $this->checkout(array_replace($this->all(), $data));
        }

        /**
         *
         * Sort an array in reverse order and maintain index association
         *
         * @return Collect
         *
         */
        public function arsort(): Collect
        {
            arsort($this->data);
            return $this;
        }

        /**
         *
         * Sort the array and maintain index association
         *
         * @param int $flag
         *
         * @return Collect
         *
         */
        public function asort(int $flag = SORT_REGULAR): Collect
        {
            asort($this->data, $flag);
            return $this;
        }

        /**
         *
         * Removes an item from the collection by its key
         *
         *
         * @param array<mixed> $keys
         *
         * @return Collect
         *
         */
        public function forget(array $keys): Collect
        {
            return  $this->del($keys);
        }

        /**
         *
         * Removes duplicate values from an array
         *
         * @return Collect
         *
         */
        public function unique(): Collect
        {
            return $this->checkout(array_unique($this->all()));
        }

        /**
         *
         * Take specified items in the array
         *
         * @param int $x
         *
         * @return Collect
         *
         */
        public function take(int $x): Collect
        {
            return $this->checkout($this->chunk($x)->first());
        }

        /**
         *
         * Calculate the product of values in an array
         *
         * @return int|float
         *
         */
        public function product()
        {
            return array_product($this->all());
        }

        /**
         *
         *
         * @param callable $callable
         *
         * @return Collect
         *
         */
        public function map($callable)
        {
            return $this->checkout(array_map($callable, $this->all()));
        }

        /**
         *
         *
         *
         * @param callable $callable
         *
         * @return Collect
         *
         */
        public function reduce($callable): Collect
        {
            return $this->checkout(array_reduce($this->all(), $callable));
        }

        /**
         *
         * Filters elements of the array
         *
         *
         * @param callable $callable
         * @param int      $flag
         *
         * @return Collect
         *
         */
        public function filter($callable, int $flag = 0): Collect
        {
            return $this->checkout(array_filter($this->all(), $callable, $flag));
        }

        /**
         *
         * Display element for a page
         *
         *
         * @param int $page
         * @param int $limit
         *
         * @return Collect
         *
         */
        public function display(int $page, int $limit): Collect
        {
            return $this->slice($page, $limit);
        }

        /**
         *
         * Sort the array
         *
         * @param int $flag
         *
         * @return Collect
         *
         */
        public function sort(int $flag = SORT_REGULAR): Collect
        {
            sort($this->data, $flag);
            return $this;
        }


        /**
         *
         * Exchanges all keys with their associated values in an array
         *
         *
         * @return Collect
         *
         */
        public function flip(): Collect
        {
            return $this->checkout(array_flip($this->data));
        }

        /**
         *
         * Shuffle an array
         *
         *
         * @return Collect
         *
         */
        public function shuffle(): Collect
        {
            shuffle($this->data);
            return $this->checkout($this->all());
        }

        /**
         *
         * @param int $flag
         *
         * @return Collect
         *
         */
        public function krsort(int $flag = SORT_REGULAR): Collect
        {
            krsort($this->data, $flag);
            return $this;
        }

        /**
         *
         * @param int $flag
         *
         * @return Collect
         *
         */
        public function ksort(int $flag = SORT_REGULAR): Collect
        {
            ksort($this->data, $flag);
            return $this;
        }

        /**
         *
         * Create an array containing a range of elements
         *
         *
         * @param int $start
         * @param int $end
         * @param int $step
         *
         * @return Collect
         *
         */
        public function range(int $start, int $end, int $step = 1): Collect
        {
            return $this->checkout(range($start, $end, $step));
        }

        /**
         *
         * Assign a new value to an existing value
         *
         *
         * @param mixed $old_value
         * @param mixed $new_value
         *
         * @return Collect
         *
         */
        public function refresh($old_value, $new_value): Collect
        {
            if ($old_value !== $new_value) {
                foreach ($this->all() as $k => $v) {
                    if ($v === $old_value && $this->has($k)) {
                        $this->data[$k] = $new_value;
                    }
                }
            }
            return $this;
        }

        /**
         *
         * Add values to the end of the array
         *
         *
         * @param mixed $value
         *
         * @return Collect
         *
         */
        public function push($value): Collect
        {
            array_push($this->data, $value);

            return $this->checkout($this->data);
        }


        /**
         *
         * Add the values to the begin ot the array
         *
         * @param mixed $value
         *
         * @return Collect
         *
         */
        public function stack($value): Collect
        {
            array_unshift($this->data, $value);

            return $this->checkout($this->data);
        }

        /**
         *
         * Merge multiples array
         *
         * @param array<mixed> $array
         *
         * @return Collect
         *
         */
        public function merge(array $array): Collect
        {
            $this->data = array_merge($this->data, $array);
            return $this->checkout($this->data);
        }

        /**
         *
         * Defines only values
         *
         * @param mixed $value
         *
         * @return Collect
         *
         */
        public function set($value): Collect
        {
            return  $this->add($value);
        }

        /**
         *
         * Add the value accessible by the key
         *
         * @param mixed $key
         * @param mixed $value
         *
         * @return Collect
         *
         */
        public function put($key, $value): Collect
        {
            return $this->add($value, $key);
        }


        /**
         *
         * Add a value accessible or not by a key
         *
         * @param mixed $value
         * @param string $key
         * @return Collect
         */
        public function add($value, string $key = ''): Collect
        {
            def($key) ? $this->data[$key] = $value : $this->data[] = $value;

            return $this;
        }
        /**
         *
         * Remove values or keys inside the array
         *
         * @param array<mixed> $data
         *
         * @return Collect
         *
         */
        public function del(array $data): Collect
        {
            foreach ($data as $datum) {
                $this->exist($datum) ? $this->removeValue([$datum]) : $this->remove([$datum]);
            }

            return $this->checkout($this->all());
        }

        /**
         *
         * Join all values by a glue
         *
         * @param string $glue
         *
         * @return string
         *
         */
        public function join(string $glue = ','): string
        {
            return implode($glue, $this->values()->all());
        }

        /**
         *
         * Join all  keys by a glue
         *
         * @param string $glue
         *
         * @return string
         *
         */
        public function joinKeys(string $glue = ','): string
        {
            return implode($glue, $this->keys()->all());
        }

        /**
         *
         * Check if false was not found
         *
         * @return bool
         *
         */
        public function ok(): bool
        {
            return $this->notExist(false);
        }

        /**
         *
         * Check if a value exist in the array
         *
         * @param mixed $value
         *
         * @return bool
         *
         **/
        public function exist($value): bool
        {
            return in_array($value, $this->data);
        }

        /**
         *
         * Check if a key exist in the array
         *
         * @param int|string $key
         *
         * @return bool
         *
         */
        public function has($key): bool
        {
            return array_key_exists($key, $this->all());
        }

        /**
         *
         * Check if the value not exist in the array
         *
         * @param mixed $value The value to check
         *
         * @return bool
         *
         */
        public function notExist($value): bool
        {
            return !$this->exist($value);
        }

        /**
         *
         * Clear the array
         *
         * @return Collect
         *
         */
        public function clear(): Collect
        {
            return $this->checkout([]);
        }

        /**
         *
         * Return the number of elements in the array
         *
         * @return int
         *
         */
        public function sum(): int
        {
            return count($this->all());
        }

        /**
         *
         * Remove the first element
         *
         * @return Collect
         *
         */
        public function shift(): Collect
        {
            array_shift($this->data);

            return $this->checkout($this->data);
        }

        /**
         *
         * Remove the last element
         *
         * @return Collect
         *
         */
        public function pop(): Collect
        {
            array_pop($this->data);

            return $this->checkout($this->data);
        }

        /**
         *
         * Checkout on the new array
         *
         * @param array<mixed> $data
         *
         * @return Collect
         *
         */
        public function checkout(array $data): Collect
        {
            return new self($data);
        }

        /**
         *
         * Convert the array in a json string
         *
         * @return string
         *
         */
        public function json(): string
        {
            $x = json_encode($this->all(), JSON_FORCE_OBJECT);

            return  is_bool($x) ? '' : $x;
        }



        /**
         *
         * Run callable function for each values  and keys in the array
         *
         * @param callable $callable The function to use for each keys and values.
         *
         * @return Collect
         *
         */
        public function each(callable $callable): Collect
        {
            $result = collect();
            foreach ($this->all() as $k => $v) {
                $result->put($k, call_user_func_array($callable, [$k, $v]));
            }

            return $this->checkout($result->all());
        }

        /**
         *
         * Execute the callable with all value in the array
         *
         * @param callable $callable The function to use for each values.
         *
         * @return Collect
         *
         */
        public function for(callable $callable): Collect
        {
            $result = collect();
            foreach ($this->all() as $k => $items) {
                $result->add(call_user_func_array($callable, [$items]));
            }
            return $this->checkout($result->all());
        }

        /**
         *
         * Initialize all keys to value
         *
         * @param mixed $value
         *
         * @return Collect
         *
         */
        public function init($value): Collect
        {
            return $this->checkout(array_fill_keys($this->keys()->all(), $value));
        }

        /**
         *
         * Add the value only if not exist
         *
         * @param array<mixed> $values
         *
         * @return Collect
         *
         */
        public function uniq(array $values): Collect
        {
            foreach ($values as $value) {
                if ($this->notExist($value)) {
                    $this->push($value);
                }
            }
            return $this;
        }

        /**
         *
         * Save an object to be called more later.
         *
         * @param string $key The object key.
         * @param object $object The object to save.
         *
         * @return Collect
         *
         */
        public function addObject(string $key, object $object): Collect
        {
            $this->data[$key] = serialize($object);
            return $this;
        }

        /**
         *
         * Get the object instance.
         *
         * @param string $key The object key.
         *
         * @return object
         *
         */
        public function getObject(string $key): object
        {
            return $this->has($key) ? unserialize($this->data[$key]) : $this;
        }

        /**
         *
         * Save a callback to be called more later.
         *
         * @param string $key The callback key.
         * @param Closure $value The callback code.
         * @param array $args The callback arguments.
         *
         * @return Collect
         *
         */
        public function addCallback(string $key, Closure $value, array $args = []): Collect
        {
            SerializableClosure::setSecretKey(env('SECURE_KEY'));
            $this->data[$key] = serialize(new SerializableClosure($value));
            $this->data["$key-args"] = $args;

            return $this;
        }

        /**
         *
         * Get a callback saved in the collection.
         *
         * @param string $key The callback key.
         *
         * @return Closure
         *
         */
        public function getCallback(string $key): Closure
        {
            $x = function () {
            };

            return $this->has($key) ?  unserialize($this->get($key))->getClosure() : $x;
        }

        /**
         *
         * Get all callable arguments.
         *
         * @param string $key The callback key.
         *
         * @return array
         *
         */
        public function getCallbackArguments(string $key): array
        {
            return $this->has($key) ? $this->data["$key-args"] : [];
        }

        /**
         *
         * Call a callable function from the array.
         *
         * @param string $key The callback key.
         *
         * @return mixed
         *
         */
        public function call(string $key)
        {
            if ($this->has($key)) {
                SerializableClosure::setSecretKey(env('SECURE_KEY'));
                return call_user_func_array(
                    $this->getCallback($key),
                    $this->getCallbackArguments($key)
                );
            }
            return null;
        }

        /**
         *
         * Remove a data by a key
         *
         * @param array<mixed> $keys
         *
         * @return Collect
         *
         */
        private function remove(array $keys): Collect
        {
            foreach ($keys as $key) {
                if ($this->has($key)) {
                    unset($this->data[$key]);
                }
            }
            return $this;
        }

        /**
         *
         * Remove a value in the array
         *
         * @param array<mixed> $values
         *
         * @return Collect
         *
         */
        private function removeValue(array $values): Collect
        {
            foreach ($values as $value) {
                if (($key = array_search($value, $this->data)) !== false) {
                    unset($this->data[$key]);
                }
            }
            return $this;
        }
    }
}
