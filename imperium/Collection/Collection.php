<?php

namespace Imperium\Collection;


use ArrayAccess;
use Iterator;

class Collection implements ArrayAccess, Iterator
{
    /**
     * @var array
     */
    private $data = array();

    /**
     * current position
     *
     * @var int
     */
    private $position;

    /**
     * @var mixed
     */
    private $beforeValue;


    /**
     * Collection constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->position = 0;
    }

    /**
     * check if array is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->data);
    }

    /**
     * init the counter
     *
     * @return int
     */
    public function init()
    {
        $this->position = 0;
        return $this->position;
    }

    /**
     * print data from a table
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
     */
    public function print( bool $printATable = true ,array $columns =[],bool $printCards = false,string $htmlHeadCode= '',string $htmlEndHead ='',string $bodyHtmlElement= '',string $bodyElementClass= '',string $bodyElementSeparator= '',string $htmlCodeBeforeAll = '<div class="row">',string $htmlCodeAfterAll = '</div>'): string
    {
        $this->rewind();


        if (!$printATable && !$printCards)
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
        }

        if ($printATable && !$printCards)
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
        return '';

    }

    /**
     * get
     * @return array
     */
    public function getCollection(): array
    {
        return $this->data;
    }

    /**
     * add data in the array
     *
     * @param mixed $values
     *
     * @return Collection
     */
    public function push(...$values): Collection
    {
        foreach ($values as $value)
            push($this->data,$value);


        return $this;
    }

    /**
     * create a stack
     *
     * @param mixed $values
     *
     * @return Collection
     */
    public function stack(...$values): Collection
    {
        foreach ($values as $value)
            stack($this->data,$value);

        return $this;
    }

    /**
     * @param array ...$array ...$array
     *
     * @return Collection
     */
    public function merge(array ...$array): Collection
    {
        foreach($array as  $v)
            merge($this->data,$v);

        return $this;
    }

    /**
     * get the last element in an array
     *
     * @return mixed
     */
    public function end()
    {
        return end($this->data);
    }

    /**
     * get the first element of an array
     *
     * @return mixed
     */
    public function start()
    {
        return reset($this->data);
    }

    /**
     * count the total of elements in the array
     *
     * @return int
     */
    public function length(): int
    {
        return count($this->data);
    }

    /**
     * define a value with a key
     *
     * @param string $key
     * @param $value
     *
     * @return Collection
     */

    public function set($value,$key = ''): Collection
    {
        if (not_def($key))
        {
            $this->data[] = $value;
        } else {
            $this->data[$key] = $value;
        }
        return $this;

    }

    /**
     * reverse an array
     *
     * @param bool $preserveKey
     *
     * @return array
     */
    public function reverse($preserveKey= false): array
    {
        return array_reverse($this->data,$preserveKey);
    }


    /**
     * get the value of the array before a key
     *
     * @param $key
     *
     * @return mixed
     */
    public function valueBeforeKey($key)
    {
        $length = $this->length();
        if ($this->has($key) && $length > 1)
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
        return $key;
    }

    /**
     * verify if a key exist in the array
     *
     * @param $key
     *
     * @return bool
     */
    public function has($key): bool
    {
        return key_exists($key,$this->data);
    }

    /**
     * get the values of array
     *
     * @return array
     */
    public function getValues(): array
    {
        return values($this->data);
    }

    /**
     * get the keys of array
     *
     * @return array
     */
    public function getKeys(): array
    {
        return array_keys($this->data);
    }

    /**
     * get the before value in the array
     *
     * @return mixed
     */
    public function before()
    {
        $this->position--;
        return $this->current();
    }

    /**
     * get the prev value in the array
     *
     * @return mixed
     */
    public function after()
    {
        $this->position++;
        return $this->current();
    }


    /**
     * verify if a value exist in an array
     *
     * @param $value
     *
     * @return bool
     */
    public function exist($value): bool
    {
        return has($value,$this->data);
    }

    /**
     * verify is a value nt exist
     *
     * @param $value
     *
     * @return bool
     */
    public function notExist($value): bool
    {
        return ! $this->exist($value);
    }

    /**
     * verify if value is numeric
     *
     * @param $value
     *
     * @return bool
     */
    public function isNumeric($value): bool
    {
        return is_numeric($value);
    }

    /**
     * verify is a value is a string
     *
     * @param $value
     *
     * @return bool
     */
    public function isString($value): bool
    {
        return is_string($value);
    }

    /**
     * get the value of a key in the array
     *
     * @param $key
     *
     * @return bool|mixed
     */
    public function get($key)
    {
        return $this->has($key) ? $this->data[$key] : '';

    }

    /**
     * remove a value in the array
     *
     * @param $key
     *
     * @return Collection
     */
    public function remove($key): Collection
    {
        if ($this->has($key))
          unset($this->data[$key]);

        return $this;
    }

    /**
     * join values by a the glue
     *
     * @param string $glue
     *
     * @param bool $replace
     * @param string $search
     * @param string $new_value
     *
     * @return string
     */
    public function join(string $glue,bool $replace = false,string $search= '',string $new_value =''): string
    {
        $code = implode($glue,$this->data);
        if ($replace)
            $code = str_replace($search,$new_value,$code);

        return $code;
    }


    /**
     * empty the data
     *
     * @return Collection
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
        $this->set($offset,$value);
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
        ++$this->position;
    }
}