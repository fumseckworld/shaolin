<?php

namespace Imperium\Collection;


use ArrayAccess;
use Iterator;

/**
 * Management of array
 *
 * Class Collection
 *
 * @package Imperium\Collection
 */
class Collection implements ArrayAccess, Iterator
{

    /**
     *
     * the array to manage
     *
     * @var array
     *
     */
    private $data = array();

    /**
     *
     * the current position
     *
     * @var int
     *
     */
    private $position;

    /**
     *
     * the value before a key
     *
     * @var mixed
     *
     */
    private $beforeValue;

    /**
     *
     * Collection constructor.
     *
     * Save or create an array to manage
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
     * @param callable $callable
     *
     * @return Collection
     */
    public function each(callable $callable): Collection
    {
        $result = collection();
        foreach ($this->data as $datum)
        {
            $result->push($callable($datum));
        }
        $this->data  = $result->collection();
        return $this;
    }


    /**
     *
     * check if array is empty
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
     * Initialize the position to 0 and return the position
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
     * Generate personal, table, card code to see records information
     *
     * @param bool $printATable
     * @param bool $printCards
     * @param array $columns
     * @param string $htmlHeadCode
     * @param string $htmlEndHead
     * @param string $bodyHtmlElement
     * @param string $bodyElementClass
     * @param string $bodyElementSeparator
     * @param string $htmlCodeBeforeAll
     * @param string $htmlCodeAfterAll
     *
     * @return string
     *
     */
    public function print( bool $printATable = true ,array $columns =[],bool $printCards = false,string $htmlHeadCode= '',string $htmlEndHead ='',string $bodyHtmlElement= '',string $bodyElementClass= '',string $bodyElementSeparator= '',string $htmlCodeBeforeAll = '<div class="row">',string $htmlCodeAfterAll = '</div>'): string
    {
        $this->rewind();


        if (!$printATable && $printCards)
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
        }elseif ($printATable && !$printCards)
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
        }else
        {

            $code = '';
            append($code,$htmlCodeBeforeAll);
            while ($this->valid())
            {
                $values = $this->current();

                append($code,$htmlHeadCode,'<'.$bodyHtmlElement.' class="'.$bodyElementClass.'">');


                foreach ($values as $k => $v)
                {
                    append($code,"<$bodyElementSeparator> {$values->$k} </$bodyElementSeparator>");

                }

                append($code,'</'.$bodyHtmlElement.'>',$htmlEndHead);


                $this->next();
            }
            append($code,$htmlCodeAfterAll);
            return $code;
        }

    }

    /**
     *
     * get the array modified
     *
     * @return array
     *
     */
    public function collection(): array
    {
        return $this->data;
    }

    /**
     *
     *  Add to the end of the array
     *
     * @param mixed $values
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
     * Add to the begin of the array
     *
     * @param mixed $values
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
     * Merge multiple array inside the array
     *
     * @param array ...$array
     *
     * @return Collection
     */
    public function merge(array ...$array): Collection
    {
        foreach($array as  $x)
           $this->data = array_merge($this->data,$x);

        return $this;
    }

    /**
     *
     * Return the last element inside the array
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
     * Return the first element inside the array
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
     * Return the number of elements inside the array
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
     * Add inside the array a value with and optional key
     *
     * @param string $key
     * @param $value
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
     * Return the reverse of the array
     *
     * @param bool $preserveKey
     *
     * @return array
     *
     */
    public function reverse($preserveKey= false): array
    {
        return array_reverse($this->data,$preserveKey);
    }


    /**
     *
     * Return the value of the array before a key
     *
     * @param $key
     *
     * @return mixed
     *
     */
    public function value_before_key($key)
    {
        $length = $this->length();
        if ($this->has_key($key) && $length > 1)
        {

            foreach ($this->data as $k => $v)
                if($k !== $key)
                    $this->beforeValue = $v;
                else
                    return $this->beforeValue;

        }

        if ($length > 1)
        {
            foreach ($this->data as $v)
                if($v !== $key)
                    $this->beforeValue = $v;
                else
                    return $this->beforeValue;
        }
        return $this->has_key($key) ? $this->data[$key] : $key;

    }

    /**
     *
     * Check if the key exist inside the array
     *
     * @param $key
     *
     * @return bool
     *
     */
    public function has_key($key): bool
    {
        return key_exists($key,$this->data);
    }

    /**
     * get the values of array
     *
     * @return array
     */
    public function values(): array
    {
        return array_values($this->data);
    }

    /**
     * get the keys of array
     *
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->data);
    }

    /**
     *
     * Move the current position before
     * the current position
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
     * Move the current position after
     * the current position
     * and return the current value
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
     * Check if the value exist inside the array
     *
     * @param $value
     *
     * @return bool
     *
     */
    public function exist($value): bool
    {
        return in_array($value,$this->data);
    }

    /**
     *
     * Check if the value not exist inside the array
     *
     * @param $value
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
     * Check if the value is numeric
     *
     * @param $value
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
     * @param $value
     *
     * @return bool
     *
     */
    public function string($value): bool
    {
        return is_string($value);
    }

    /**
     *
     * Get a value inside the array by a key
     *
     * @param $key
     *
     * @return mixed
     *
     */
    public function get($key)
    {
        return $this->has_key($key) ? $this->data[$key] : '';
    }

    /**
     *
     * Remove a value inside the array by a key
     *
     * @param $key
     *
     * @return Collection
     *
     */
    public function remove($key): Collection
    {
        if ($this->has_key($key))
            unset($this->data[$key]);

           return $this;
    }

    public function remove_values(string ...$values)
    {
        foreach ($values as $value)
        {
            unset($this->data[array_search($value, $this->data)]);
        }

        return $this;
    }


    /**
     *
     * Join all values inside the array by a string
     *
     * @param string $glue
     * @param bool $replace
     * @param string $search
     * @param string $new_value
     *
     * @return string
     *
     */
    public function join(string $glue,bool $replace = false,string $search= '',string $new_value =''): string
    {
        $code = implode($glue,$this->data);
        if ($replace)
            $code = str_replace($search,$new_value,$code);

        return $code;
    }


    /**
     *
     * Empty the array
     *
     * @return Collection
     *
     */
    public function clear(): Collection
    {
        $this->data = array();

        return $this;
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
}