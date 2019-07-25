<?php

namespace Imperium\Collection {

    use ArrayAccess;
    use Imperium\Exception\Kedavra;
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
    class Collection implements ArrayAccess, Iterator
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

            $this->position = 0;
        }


        /**
         *
         * Count all values found
         *
         * @method count
         *
         * @return Collection
         *
         */
        public function count(): Collection
        {
            return $this->checkout(array_count_values($this->all()));
        }

        /**
        *
        * Initialize the position to i and return the position
        *
        * @param int $i
        *
        * @return int
        */
        public function init(int $i = 0)
        {
            $this->position = $i;

            return $this->position;
        }

        /**
         *
         * Convert the data in the array to html code
         *
         * @method print
         *
         * @param  bool $html_table To print a html table
         * @param  array $columns Print defined columns name
         * @param  bool $html_cards To print cards
         * @param  string $html_head_code The customize html head code
         * @param  string $html_end_head_code The customize html end head code
         * @param  string $html_body_element The body code
         * @param  string $body_class The body class
         * @param  string $body_elements_separator The html tag to separate the elements
         * @param  string $html_before_all The html code to add before all
         * @param  string $html_after_all The html code to add after_all
         *
         * @return string
         *
         */
        public function print( bool $html_table = true ,array $columns =[],bool $html_cards = false,string $html_head_code= '',string $html_end_head_code ='',string $html_body_element = '',string $body_class= '',string $body_elements_separator= '',string $html_before_all = '<div class="row">',string $html_after_all = '</div>'): string
        {
            $this->rewind();


            if (!$html_table && $html_cards)
            {
                $code = '';

                append($code,'<div class="row">');


                while ($this->valid())
                {
                    $values = $this->current();

                    append($code,'<div class="col-lg-4"><div class="card ml-4 mr-4 mt-4 mb-4"><div class="card-body">');


                    foreach ($values as $k => $v)
                    {
                        append($code,"<p> {$values->$k} </p>");

                    }

                    append($code,'</div></div></div>');

                    $this->next();
                }
                append($code,'</div>');

                return $code;

            }

            if ($html_table && !$html_cards)
            {
                $code = '';

                append($code,'<table class="table table-bordered table-hover"><thead><tr>');

                foreach ($columns as $column)
                {
                    append($code, '<th>'.$column.'</th>');

                }

                append($code,'</tr></thead><tbody>');

                while ($this->valid())
                {

                    $values = $this->current();
                    append($code,'<tr>');

                    foreach ($values as $k => $v)
                    {
                        append($code,"<td> {$values->$k} </td>");

                    }

                    append($code,'</tr> ');


                    $this->next();
                }
                append($code,'</tbody></table>');
                return $code;
            }

            if (!$html_table && !$html_cards)
            {

                $code = '';
                append($code,$html_before_all);
                while ($this->valid())
                {
                    $values = $this->current();

                    append($code,$html_head_code,'<'.$html_body_element.' class="'.$body_class.'">');


                    foreach ($values as $k => $v)
                    {
                        append($code,"<$body_elements_separator> {$values->$k} </$body_elements_separator>");

                    }

                    append($code,'</'.$html_body_element.'>',$html_end_head_code);


                    $this->next();
                }

                append($code,$html_after_all);

                return $code;
            }
            return '';
        }

        /**
        *
        * Return the array generated
        *
        * @method collection
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
         * @return Collection
         *
         */
        public function only(...$keys): Collection
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
         * Compare two array
         *
         * @method diff
         *
         * @param array $x
         *
         * @return Collection
         *
         */
        public function diff(array $x): Collection
        {
            return $this->checkout(array_diff($this->all(),$x));
        }

        /**
        *
        * Return the last element in the array
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
        * Return the begin of the array
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
         * @return Collection
         */
        public function reverse(): Collection
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
         * @throws Kedavra
         *
         */
        public function value_before_key($key)
        {
            $length = $this->sum();

            if ($this->has($key) && superior($length,1))
            {

                foreach ($this->data as $k => $v)
                    if(different($k,$key))
                        $this->beforeValue = $v;
                    else
                        return $this->beforeValue;

            }

            if (superior($length,1))
            {
                foreach ($this->data as $v)
                    if(different($v,$key))
                        $this->beforeValue = $v;
                    else
                        return $this->beforeValue;
            }

            return $this->has($key) ? $this->data[$key] : $key;

        }

        /**
        *
        * Return all values
        *
        * @method values
        *
        * @return array
        *
        */
        public function values(): array
        {
            return array_values($this->all());
        }

        /**
        *
        * Return all keys
        *
        * @method keys
        *
        * @return array
        *
        */
        public function keys(): array
        {
            return array_keys($this->all());
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
        * Get a value in the array by a key
        *
        * @method get
        *
        * @param mixed $key The value key
        *
        * @return mixed
        *
        **/
        public function get($key)
        {
            return $this->has($key) ? $this->data[$key] : '';
        }

        /**
         * @param $key
         *
         * @return bool
         *
         */
        public function key_not_exist($key): bool
        {
            return  ! $this->has($key);
        }




        /**
        * Whether a offset exists
        * @link https://php.net/manual/en/arrayaccess.offsetexists.php
        * @param mixed $offset <p>
        * An offset to check for.
        * </p>
        * @return boolean true on success or false on failure.
        * </p>
        * <p>
        * The return value will be casted to boolean if non-boolean was returned.
        * @since 5.0.0
        */
        public function offsetExists($offset)
        {
            return $this->has($offset);
        }

        /**
        * Offset to retrieve
        * @link https://php.net/manual/en/arrayaccess.offsetget.php
        * @param mixed $offset <p>
        * The offset to retrieve.
        * </p>
        * @return mixed Can return all value types.
        * @since 5.0.0
        */
        public function offsetGet($offset)
        {
            return $this->get($offset);
        }

        /**
        * Offset to set
        * @link https://php.net/manual/en/arrayaccess.offsetset.php
        * @param mixed $offset <p>
        * The offset to assign the value to.
        * </p>
        * @param mixed $value <p>
        * The value to set.
        * </p>
        * @return void
        * @since 5.0.0
        */
        public function offsetSet($offset, $value)
        {
            $this->add($value,$offset);
        }

        /**
        * Offset to unset
        * @link https://php.net/manual/en/arrayaccess.offsetunset.php
        * @param mixed $offset <p>
        * The offset to unset.
        * </p>
        * @return void
        * @since 5.0.0
        */
        public function offsetUnset($offset)
        {
            $this->remove($offset);
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
         * @return Collection
         *
         *
         */
        public function refresh($old_value,$new_value): Collection
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
         * @return Collection
         *
         */
        public function push(...$values): Collection
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
         * @return Collection
         *
         */
        public function stack(...$values): Collection
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
         * @return Collection
         *
         */
        public function merge(array ...$array): Collection
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
         * @return Collection
         *
         */
        public function set(...$values): Collection
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
         * @return Collection
         *
         */
        public function put($key,$value): Collection
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
         * @return Collection
         *
         */
        public function del(...$data): Collection
        {
            foreach ($data as $datum)
                $this->exist($datum) ? $this->remove_value($datum) : $this->remove($datum);

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
           return implode($glue,$this->values());
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
            return implode($glue,$this->keys());
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
         * @return Collection
         *
         */
        public function clear(): Collection
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
         * @return Collection
         *
         */
        public function shift(): Collection
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
         * @return Collection
         *
         */
        public function pop(): Collection
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
         * @return Collection
         *
         */
        public function checkout(array $data): Collection
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
         * Run callable function for each values in the array
         *
         * @method each
         *
         * @param callable $callable
         *
         * @return Collection
         *
         */
        public function each($callable): Collection
        {
            $result = collect();

            foreach ($this->all() as $value)
                $result->set($callable($value));

            return $this->checkout($result->all());
        }

        /**
         *
         * Add a uniq value
         *
         * @method uniq
         *
         * @param array $values
         * @return Collection
         */
        public function uniq(...$values): Collection
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
         * @return Collection
         *
         */
        private function add($value,$key = ''): Collection
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
         * @return Collection
         */
        private function remove(...$keys): Collection
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
         * @return Collection
         */
        private function remove_value(...$values): Collection
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
