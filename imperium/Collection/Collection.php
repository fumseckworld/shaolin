<?php

namespace Imperium\Collection {

    use ArrayAccess;
    use Imperium\Exception\Kedavra;
    use Imperium\File\File;
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
        * The search result
        *
        * @var mixed
        *
        */
        private $search;

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
         * Convert the data in the array to a json file
         *
         * @method convert_to_json
         *
         * @param  string $filename The json filename
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function convert_to_json(string $filename): bool
        {
            return json($filename)->create($this->data);
        }

        /**
         *
         * Get a value
         *
         * @param mixed $prefix
         * @param mixed $key
         *
         * @return mixed|null
         *
         */
        public function value($prefix,$key)
        {
            return array_key_exists($prefix,$this->data) ? $this->data[$prefix][$key] : null;
        }

        /**
         *
         * Assign a value
         *
         * @param $prefix
         * @param $value
         * @param string $key
         *
         * @return Collection
         *
         */
        public function double($prefix,$value,$key): Collection
        {
            def($key) ?  $this->data[$prefix][$key] = $value : $this->data[$prefix][] = $value;

           return $this;
        }

        /**
        *
        * Define the new data
        *
        * @param array $data
        *
        * @return Collection
        *
        */
        public function set_new_data(array $data): Collection
        {
            $this->data = $data;

            return $this;
        }

        /**
         *
         * Convert the array in a json string
         *
         * @method json
         *
         * @return string [description]
         */
        public function json(): string
        {
            return json_encode($this->collection(),JSON_FORCE_OBJECT);
        }

        /**
        *
        * Run callable function for each values in the array
        *
        * @param callable $callable
        *
        * @return Collection
        *
        */
        public function each(callable $callable): Collection
        {
            $result = collection();

            foreach ($this->data as $datum)
                $result->push($callable($datum));

            $this->set_new_data($result->collection());

            return $this;
        }

        /**
        *
        * Search a value in the array
        *
        * @param $value
        *
        * @return Collection
        *
        */
        public function search($value): Collection
        {
            $this->search = array_search($value,$this->data);

            return $this;
        }

        /**
        *
        * Get the search result
        *
        * @return mixed
        *
        */
        public function get_search()
        {
            return $this->search;
        }

        /**
         *
         * Return the result value
         *
         * @param bool $length
         *
         * @return mixed
         */
        public function result(bool $length = false)
        {
            if ($length)
            {
                $x = collection(explode('(',trim($this->get($this->get_search()),')')));
                return $x->has_key(1) ? $x->get(1) : 0;
            }

            $x = collection(explode('(',trim($this->get($this->get_search()),')')));
            return $x->has_key(0) ? $x->get(0) : '';
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
            return empty($this->data);
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
        public function collection(): array
        {
            return $this->data;
        }

        public function find(string $pattern)
        {
             $x =  array_search($pattern,$this->data);

             return is_bool($x) ? $x : $this->get($x);
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
         * @return array
         */
        public function count_values(): array
        {
            return array_count_values($this->data);
        }

        /**
         * @param $expected
         * @return array
         */
        public function data(array $expected): array
        {

            $data = collection();
            foreach ($this->collection() as $k => $v)
            {

                if (is_numeric($k))
                {
                    if (has($v,$expected))
                        $data->add($v);
                }else
                {
                    if (has($v,$expected))
                        $data->add($k);

                }

            }
            return $data->collection();
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
        * @param array[]  The list of array to merge
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
        * @method begin
        *
        * @return mixed
        *
        */
        public function begin()
        {
            return reset($this->data);
        }

        /**
        *
        * Return the number of elements in the array
        *
        * @method length
        *
        * @return int
        *
        */
        public function length(): int
        {
            return count($this->data);
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
        public function add($value,$key = ''): Collection
        {
            not_def($key) ?  $this->data[] = $value :  $this->data[$key] = $value;

            return $this;
        }


        /**
         *
         * Add a value if not exist
         *
         * @param $value
         * @param string $key
         *
         * @return Collection
         *
         */
        public function add_if_not_exist($value,$key =''): Collection
        {
            if ($this->not_exist($value))
                $this->add($value, $key);

            return $this;
        }
        /**
        *
        * Return the reverse of the array
        *
        * @method reverse
        *
        * @param  bool    $preserveKey
        *
        * @return array
        *
        */
        public function reverse($preserveKey = false): array
        {
            return array_reverse($this->data,$preserveKey);
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
            $length = $this->length();

            if ($this->has_key($key) && superior($length,1))
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

            return $this->has_key($key) ? $this->data[$key] : $key;

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
        public function has_key($key): bool
        {
            return is_string($key) || is_numeric($key) ? key_exists($key,$this->data) : false;
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
            return array_values($this->data);
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
            return array_keys($this->data);
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
         * Check if a value is in an array in data
         *
         * @param string $value
         *
         * @return bool
         *
         */
        public function contains(string $value): bool
        {
            foreach ($this->data as $datum)
                if (in_array($value,$datum))
                    return true;

            return false;
        }

        /**
         *
         * Check if value not exist in an array of data
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
        * Check if the value is numeric
        *
        * @method numeric
        *
        * @param  mixed  $value The value to check
        *
        * @return bool
        *
        */
        public function numeric($value): bool
        {
            return is_numeric($value);
        }

        /**
        *
        * Check if the value is a string
        *
        * @method string
        *
        * @param mixed $value The value to check
        *
        * @return bool
        *
        */
        public function string($value): bool
        {
            return is_string($value);
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
            return $this->has_key($key) ? $this->data[$key] : '';
        }

        public function get_key()
        {
            return collection($this->keys())->get(0);
        }

        /**
         * @param $key
         *
         * @return bool
         *
         */
        public function key_not_exist($key): bool
        {
            return  ! $this->has_key($key);
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
        public function remove(...$keys): Collection
        {

            foreach ($keys as $key)
            {
                if ($this->has_key($key))
                    unset($this->data[$key]);
            }


            return $this;
        }

        /**
         * @param $key
         * @param $value
         *
         * @return Collection
         *
         */
        public function set($key,$value)
        {
            $this->data[$key] = $value;
            return $this;
        }

        /**
         *
         * Remove a value in the array
         *
         * @param array $values
         * @return Collection
         */
        public function remove_value(...$values): Collection
        {
            foreach ($values as $value)
            {
                if (($key = array_search($value, $this->data)) !== false)
                    unset($this->data[$key]);
            }

          
            return $this;
        }

        /**
        *
        * Remove multiples values
        *
        *
        * @method remove_values
        *
        * @param  string[]        $values The values to removes
        *
        * @return Collection
        *
        */
        public function remove_values(string ...$values): Collection
        {
            foreach ($values as $value)
            {
                unset($this->data[array_search($value, $this->data)]);
            }

            return $this;
        }


        /**
         *
         * Assign a new value to an existing value by a key
         *
         * @method change_value
         *
         * @param mixed $old The old value
         * @param mixed $new The new value
         *
         * @return Collection
         *
         * @throws Kedavra
         *
         */
        public function change_value($old,$new): Collection
        {
            if (different($old,$new))
            {
                foreach ($this->data as $k => $v)
                {
                    if (equal($v,$old) && $this->has_key($k))
                        $this->data[$k] = $new;
                }
            }


            return $this;
        }


        /**
         *
         * Join all values by a glue
         *
         * @method join
         *
         * @param string $glue The values separator
         * @param bool $replace Set to true to replace value
         * @param string $value The value to replace
         * @param string $new_value The new value
         *
         * @return string
         */
        public function join(string $glue,bool $replace = false,string $value= '',string $new_value =''): string
        {
            $code = implode($glue,$this->data);

            return $replace ? str_replace($value,$new_value,$code): $code;
        }

        public function join_keys(string $glue,bool $replace = false,string $value= '',string $new_value ='')
        {
            $code = implode($glue,$this->keys());

            return $replace ? str_replace($value,$new_value,$code): $code;
        }


        /**
        *
        * Empty the array
        *
        * @method clear
        *
        * @return Collection
        *
        */
        public function clear(): Collection
        {
            $this->set_new_data([]);

            return $this;
        }

        /**
         *
         * Check if values was inside the array
         *
         * @param array $data
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function inside(array $data): bool
        {
            foreach ($data as $datum)
                if (is_true($this->not_exist($datum)))
                    return false;


            return true;
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
            return $this->has_key($offset);
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
            $this->add($offset,$value);
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
            return $this->data[$this->position];
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
         *
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
         * @return Collection
         *
         */
        public function pop(): Collection
        {
            array_pop($this->data);

            return $this;
        }

        public function file()
        {
            $f = new File('README.md',EMPTY_AND_WRITE_FILE_MODE);
            foreach ($this->data as $datum)
                $f->write_line($datum);


            return $f;

        }
    }
}
