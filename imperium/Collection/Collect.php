<?php

namespace Imperium\Collection {

    use Iterator;
    /**
    *
    * Array management
    *
    * @author Willy Micieli <micieli@laposte.net>
    *
    * @package imperium
    *
    * @version 4
    *
    * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE
    *
    **/
    class Collect implements Iterator
    {

       /*
        *
        * Contains of the array
        *
        * @var array $data
        *
        */
        private $data = [];

        /**
        *
        * The current position in the array
        *
        * @var int
        *
        */
        private $position;

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
        * @method __construct
        *
        * @param array $data
        *
        */
        public function __construct(array $data = [])
        {
            $this->data = $data;

            $this->init();
        }


        /**
         *
         * Count all values found
         *
         * @method count
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
         * @method init
         *
         * @param int $i
         *
         * @return int
         *
         */
        public function init(int $i = 0): int
        {
            $this->position = $i;

            return $this->position;
        }

        /**
         *
         * Extract a slice of the array
         *
         * @method slice
         *
         * @param int $offset
         * @param int|null $limit
         *
         * @return Collect
         *
         */
        public function slice(int $offset,int $limit = null): Collect
        {
            return $this->checkout(array_slice($this->all(),$offset,$limit));
        }

        /**
         *
         *
         * Chunk the array in separate array
         *
         * @method chunk
         *
         * @param int $x
         *
         * @return Collect
         *
         */
        public function chunk(int $x): Collect
        {
            $data = collect();

            while (def($this->data))
            {
                $data->push($this->diff($this->slice($x)->all())->all());

                $this->data = $this->slice($x)->all();
            }

            return $this->checkout($data->all());

        }
        /**
        *
        * Return the array generated
        *
        * @method all
        *
        * @return array
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
         * @method only
         *
         * @param mixed ...$keys
         *
         * @return Collect
         *
         */
        public function only(...$keys): Collect
        {
            $x = collect();

            foreach ($this->all() as $k => $v)
            {
                foreach ($keys as $key)
                    is_array($v) ? $x->push([$key => $v[$key]]) :  $x->put($key,$this->get($key));
            }
            return $this->checkout($x->all());
        }

        /**
         *
         *
         * Compare the array with the x array
         *
         * @method diff
         *
         * @param array $x
         *
         * @return Collect
         *
         */
        public function diff(array $x): Collect
        {
            return $this->checkout(array_diff($this->all(),$x));
        }

        /**
        *
        * Return the last element of the array
        *
        * @method last
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
        * @method first
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
         * @method reverse
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
         * @method value_before_key
         *
         * @param  mixed $key The next value key
         *
         * @return mixed
         *
         */
        public function value_before_key($key)
        {

            if ($this->has($key))
            {

                foreach ($this->data as $k => $v)
                    if($k !== $key)
                        $this->beforeValue = $v;
                    else
                        return $this->beforeValue;

            }
            return '';

        }

       /**
        *
        * Collect all values
        *
        * @method values
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
        * @method keys
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
        * @method before
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
        * @method after
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
        * @method get
        *
        * @param mixed $key
        *
        * @return mixed
        *
        **/
        public function get($key)
        {
            return $this->has($key) ? $this->data[$key] : '';
        }

        public function max()
        {
            return max($this->all());
        }
        public function min()
        {
            return min($this->all());
        }

        /**
         *
         * Check if the array has not a key
         *
         * @method has_not
         *
         * @param $key
         *
         * @return bool
         *
         */
        public function has_not($key): bool
        {
            return  ! $this->has($key);
        }

        /**
        * Return the current element
        * @link https://php.net/manual/en/iterator.current.php
        * @return mixed Can return any type.
        * @since 5.0.0
        */
        public function current()
        {
            return $this->get($this->position);
        }

        /**
        * Return the key of the current element
        * @link https://php.net/manual/en/iterator.key.php
        * @return mixed scalar on success, or null on failure.
        * @since 5.0.0
        */
        public function key()
        {
            return $this->position;
        }

        /**
        * Checks if current position is valid
        * @link https://php.net/manual/en/iterator.valid.php
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
        * @link https://php.net/manual/en/iterator.rewind.php
        * @return void Any returned value is ignored.
        * @since 5.0.0
        */
        public function rewind()
        {
            $this->position = 0;
        }

        /**
        * Move forward to next element
        * @link https://php.net/manual/en/iterator.next.php
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
         * @method empty
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
         * Assign a new value to an existing value
         *
         * @method refresh
         *
         * @param $old_value
         * @param $new_value
         *
         * @return Collect
         *
         *
         */
        public function refresh($old_value,$new_value): Collect
        {
            if ($old_value !== $new_value)
            {
                foreach ($this->all() as $k => $v)
                {
                    if ($v === $old_value && $this->has($k))
                        $this->data[$k] = $new_value;
                }
            }

            return $this;
        }
        /**
         *
         * Add values to the end of the array
         *
         * @method push
         *
         * @param mixed[] $values The values to add
         *
         * @return Collect
         *
         */
        public function push(...$values): Collect
        {
            foreach ($values as $value)
                array_push($this->data,$value);

            return $this;
        }



        /**
         *
         * Add the values to the begin ot the array
         *
         * @method stack
         *
         * @param  mixed[] $values The values to add
         *
         * @return Collect
         *
         */
        public function stack(...$values): Collect
        {
            foreach ($values as $value)
                array_unshift($this->data,$value);

            return $this;
        }

        /**
         *
         * Merge multiples array
         *
         * @method merge
         *
         * @param array[]
         *
         * @return Collect
         *
         */
        public function merge(array ...$array): Collect
        {
            foreach($array as  $x)
                $this->data = array_merge($this->data,$x);

            return $this;
        }

        /**
         *
         * Defines only values
         *
         * @method set
         *
         * @param mixed ...$values
         *
         * @return Collect
         *
         */
        public function set(...$values): Collect
        {
            foreach ($values as $value)
                $this->add($value);

            return $this;
        }

        /**
         *
         * Add the value accessible by the key
         *
         * @method put
         *
         * @param mixed $key
         * @param mixed $value
         *
         * @return Collect
         *
         */
        public function put($key,$value): Collect
        {
            return $this->add($value,$key);
        }

        /**
         *
         * Remove values or keys inside the array
         *
         * @method  del
         *
         * @param mixed ...$data
         *
         * @return Collect
         *
         */
        public function del(...$data): Collect
        {
            foreach ($data as $datum)
                $this->exist($datum) ? $this->remove_value($datum)  : $this->remove($datum);

            return $this->checkout($this->all());
        }

        /**
         *
         * Join all values by a glue
         *
         * @method join
         *
         * @param string $glue The values separator
         *
         * @return string
         *
         */
        public function join(string $glue =','): string
        {
           return implode($glue,$this->values()->all());
        }

        /**
         *
         * Join all  keys by a glue
         *
         * @method join_keys
         *
         * @param string $glue
         *
         * @return string
         *
         */
        public function join_keys(string $glue = ','): string
        {
            return implode($glue,$this->keys()->all());
        }
        
        /**
         *
         * Check if false was not found
         *
         * @method ok
         *
         * @return bool
         *
         */
        public function ok(): bool
        {
            return $this->not_exist(false);
        }

        /**
         *
         * Check if a value exist in the array
         *
         * @method exist
         *
         * @param mixed $value The value to check
         *
         * @return bool
         *
         **/
        public function exist($value): bool
        {
            return in_array($value,$this->data);
        }

        /**
         *
         * Check if a key exist in the array
         *
         * @method has_key
         *
         * @param mixed $key The key to check
         *
         * @return bool
         *
         */
        public function has($key): bool
        {
            return is_string($key) || is_numeric($key) ? key_exists($key,$this->all()) : false;
        }

        /**
         *
         * Check if the value not exist in the array
         *
         * @method not_exist
         *
         * @param mixed $value The value to check
         *
         * @return bool
         *
         */
        public function not_exist($value): bool
        {
            return ! $this->exist($value);
        }

        /**
         *
         * Clear the array
         *
         * @method clear
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
         * @method sum
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
         * @method shift
         *
         * @return Collect
         *
         */
        public function shift(): Collect
        {
            array_shift($this->data);

            return $this;
        }

        /**
         *
         * Remove the last element
         *
         * @method pop
         *
         * @return Collect
         *
         */
        public function pop(): Collect
        {
            array_pop($this->data);

            return $this;
        }

        /**
         *
         * Checkout on the new array
         *
         * @method checkout
         *
         * @param array $data
         *
         * @return Collect
         *
         */
        public function checkout(array $data): Collect
        {
            return new static($data);
        }


        /**
         *
         * Convert the array in a json string
         *
         * @method json
         *
         * @return string
         *
         */
        public function json(): string
        {
            return json_encode($this->all(),JSON_FORCE_OBJECT);
        }

        /**
         *
         * Check if a value is in an array in data
         *
         * @method contains
         *
         * @param mixed $values
         *
         * @return bool
         */
        public function contains(...$values): bool
        {
            foreach ($this->all() as $datum)
            {
                if (is_array($datum))
                {
                    foreach ($values as $value)
                    {
                        if (is_array($datum))
                        {
                            if (in_array($value,$datum))
                                return true;
                        }
                    }
                }
            }
            return false;
        }

        /**
         *
         * Check if value not exist in an array of data
         *
         * @method not_contains
         *
         * @param string $value
         *
         * @return bool
         *
         */
        public function not_contains(string $value): bool
        {
            return ! $this->contains($value);
        }

        /**
         *
         * Run callable function for each values  and keys in the array
         *
         * @method each
         *
         * @param callable $callable
         *
         * @return Collect
         *
         */
        public function each($callable): Collect
        {
            $result = collect();

            foreach ($this->all() as  $k => $v)
                $result->set($callable($k,$v));

            return $this->checkout($result->all());
        }

        /**
         *
         * Execute the callable with all value in the array
         *
         * @method for
         *
         * @param callable $callable
         *
         * @return Collect
         *
         */
        public function for($callable): Collect
        {
            $result = collect();

            foreach ($this->all() as  $v)
                $result->set($callable($v));

            return $this->checkout($result->all());
        }

        /**
         *
         * Add the value only if not exist
         *
         * @method uniq
         *
         * @param array $values
         *
         * @return Collect
         *
         */
        public function uniq(...$values): Collect
        {
            foreach ($values as $value)
            {
                if ($this->not_exist($value))
                    $this->push($value);
            }


            return $this;
        }
        /**
         *
         * Append value to the array with optional key
         *
         * @method add
         *
         * @param  mixed $value  The value to add
         * @param  mixed $key   The value's key
         *
         * @return Collect
         *
         */
        private function add($value,$key = ''): Collect
        {
            not_def($key) ?  $this->data[] = $value :  $this->data[$key] = $value;

            return $this;
        }

        /**
         *
         * Remove a data by a key
         *
         * @method remove
         *
         * @param array $keys
         *
         * @return Collect
         *
         */
        private function remove(...$keys): Collect
        {

            foreach ($keys as $key)
            {
                if ($this->has($key))
                    unset($this->data[$key]);
            }


            return $this;
        }


        /**
         *
         * Remove a value in the array
         *
         * @param array $values
         *
         * @return Collect
         *
         */
        private function remove_value(...$values): Collect
        {
            foreach ($values as $value)
            {
                if (($key = array_search($value, $this->data)) !== false)
                    unset($this->data[$key]);
            }


            return $this;
        }
    }
}
