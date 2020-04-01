<?php

declare(strict_types=1);

namespace Eywa\Collection {


    use Iterator;

    class Collect implements Iterator
    {

        /**
         *
         * The data
         *
         * @var array<mixed>
         *
         */
        private array $data ;

        /**
         *
         * The current position in the array
         *
         */
        private int $position;

        /**
         *
         * The value before a key
         *
         * @var mixed
         *
         */
        private $beforeValue;

        /**
         *
         * Collection constructor
         *
         * @param array<mixed> $data
         *
         */
        public function __construct(array $data)
        {
            $this->data = $data;

            $this->position();
        }

        /**
         *
         * @param array<mixed> $keys
         * @param array<mixed> $values
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
         * Execute the callable and return true on success
         *
         * @param callable $callable
         *
         * @return bool
         *
         */
        public function exec($callable): bool
        {
            return array_walk($this->data, $callable);
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
         * Count all values found
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
         * Initialise position counter
         *
         * @param int $i
         *
         * @return int
         *
         */
        public function position(int $i = 0): int
        {
            $this->position = $i;
            return $this->position;
        }

        /**
         *
         * Extract a slice of the array
         *
         * @param int $offset
         * @param int|null $limit
         *
         * @return Collect
         *
         */
        public function slice(int $offset, int $limit = null): Collect
        {
            return $this->checkout(array_slice($this->all(), $offset, $limit));
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
         * Return the array generated
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
         * Get specifics keys
         *
         *
         * @param array<mixed> $keys
         *
         * @return Collect
         *
         */
        public function only(array $keys): Collect
        {
            $x = collect();
            foreach ($this->all() as $k => $v) {
                foreach ($keys as $key) {
                    is_array($v) ? $x->push([$key => $v[$key]]) : $x->put($key, $this->get($key));
                }
            }
            return $this->checkout($x->all());
        }

        /**
         *
         *
         * Compare the array with the x array
         *
         * @param array<mixed> $x
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
         * Return the last element of the array
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
         * Return the first element of the array
         *
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
         * Return the reverse of the array
         *
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
         *
         * @param string $key The next value key
         *
         * @return mixed
         *
         */
        public function valueBeforeKey(string $key)
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
            return  $this->checkout(array_values($this->all()));
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
        public function before()
        {
            $this->position--;
            return $this->current();
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
        public function after()
        {
            $this->position++;
            return $this->current();
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
            return $this->data[$key];
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
            return ! $this->exist($value);
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
         * @param callable $callable
         *
         * @return Collect
         *
         */
        public function each($callable): Collect
        {
            $result = collect();
            foreach ($this->all() as $k => $v) {
                $result->put($k, call_user_func_array($callable, [$k,$v]));
            }

            return $this->checkout($result->all());
        }

        /**
         *
         * Execute the callable with all value in the array
         *
         * @param callable $callable
         *
         * @return Collect
         *
         */
        public function for($callable): Collect
        {
            $result = collect();
            foreach ($this->all() as $k => $items) {
                $result->add(call_user_func_array($callable, [$items]));
            }
            return $this->checkout($result->all());
        }

        /**
         *
         * Initialise all keys to value
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
         * Append value to the array with optional key
         *
         * @param mixed $value The value to add
         * @param mixed $key The value's key
         *
         * @return Collect
         *
         */
        private function add($value, $key = ''): Collect
        {
            not_def($key) ? $this->data[] = $value : $this->data[$key] = $value;

            return $this;
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
