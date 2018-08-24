<?php

namespace Imperium\Core;


use ArrayAccess;
use Exception;
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
     * Collection constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->position = 0;
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
     * @throws Exception
     */
    public function print( bool $printATable = true ,array $columns =[],bool $printCards = false,string $htmlHeadCode= '',string $htmlEndHead ='',string $bodyHtmlElement= '',string $bodyElementClass= '',string $bodyElementSeparator= '',string $htmlCodeBeforeAll = '<div class="row">',string $htmlCodeAfterAll = '</div>')
    {
        $this->rewind();
        $secure = false;

        if (!$printATable && !$printCards)
        {
            _html($secure,$htmlCodeBeforeAll);
            while ($this->valid())
            {
                $values = $this->current();

                _html($secure,$htmlHeadCode);

                _html($secure,'<'.$bodyHtmlElement.' class="'.$bodyElementClass.'">');

                foreach ($values as $k => $v)
                {
                    _html($secure,"<$bodyElementSeparator> {$values->$k} </$bodyElementSeparator>");

                }

                _html($secure,'</'.$bodyHtmlElement.'>');
                _html($secure,$htmlEndHead);


                $this->next();
            }
            _html($secure,$htmlCodeAfterAll);
        }

        if (!$printATable && $printCards)
        {
            _html($secure,'<div class="row">');

            while ($this->valid())
            {
                $values = $this->current();
                _html($secure,'<div class="col-lg-4"><div class="card ml-4 mr-4 mt-4 mb-4"><div class="card-body">');

                foreach ($values as $k => $v)
                {
                    _html($secure,"<p> {$values->$k} </p>");

                }

                _html($secure,'</div></div></div>');

                $this->next();
            }
            _html($secure,'</div>');
        }

        if ($printATable && !$printCards)
        {
            _html($secure,'<table class="table table-bordered table-hover"><thead><tr>');

            foreach ($columns as $column)
            {
                _html($secure, '<th>'.$column.'</th>');

            }
            _html($secure, '</tr></thead><tbody>');

            while ($this->valid())
            {

                $values = $this->current();
                _html($secure,'<tr>');

                foreach ($values as $k => $v)
                {
                    _html($secure,"<td> {$values->$k} </td>");

                }

                _html($secure,'</tr> ');


                $this->next();
            }
            _html($secure,'</tbody></table>');
        }

        if ( $printCards &&  $printATable)
        {
            throw new Exception('Choose to build a card a able or your choose');
        }
    }

    /**
     * add data in the array
     *
     * @return Collection
     */
    public function push(): Collection
    {
        foreach (func_get_args() as $arg)
            array_push($this->data,$arg);

        return $this;
    }

    /**
     * Pop the element off the end of array
     *
     * @return Collection
     */
    public function pop(): Collection
    {
        array_pop($this->data);

        return $this;
    }

    /**
     * @param array ...$array
     *
     * @return Collection
     */
    public function merge(array ...$array): Collection
    {
        foreach($array as $elem)
            array_merge($this->data,$elem);

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
    public function total(): int
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

    public function set( $value,$key = null): Collection
    {
        if (is_null($key))
        {
            $this->data[] = $value;
        } else {
            $this->data[$key] = $value;
        }
        return $this;

    }

    /**
     * get last key in the array
     *
     * @return mixed
     */
    public function lastKey()
    {
        $array = $this->data;
        return array_keys($array)[count($array)-1];
    }

    /**
     * get the first key
     *
     * @return mixed
     */
    public function firstKey()
    {
        return reset($this->getKeys());
    }

    /**
     * get the first key
     *
     * @return mixed
     */
    public function firstValue()
    {
        return reset($this->getValues());
    }


    /**
     * get the last value in array
     *
     * @return mixed
     */
    public function lastValue()
    {
        $array = $this->data;
        return array_values($array)[count($array)-1];
    }

    /**
     * reverse an array
     *
     * @param bool $preserveKey
     *
     * @return array
     */
    public function reverse($preserveKey= true): array
    {
        return array_reverse($this->data,$preserveKey);
    }

    /**
     * verify if a key exist in the array
     *
     * @param $key
     *
     * @return bool
     */
    public function has($key ): bool
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
        return array_values($this->data);
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
     * get the prev value in the array
     *
     * @return mixed
     */
    public function prev()
    {
        return prev($this->data);
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
        return in_array($value,$this->data);
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
        if ($this->has($key))
            return $this->data[$key];

        return false;
    }

    /**
     * remove a value in the array
     *
     * @param $value
     */
    public function unset($value)
    {
        if ($this->has($value))
          unset($this->data[$value]);
    }

    /**
     * join values by a the glue
     *
     * @param string $glue
     *
     * @return string
     */
    public function join(string $glue): string
    {
        return implode($glue,$this->data);
    }

    /**
     * extract a slice of the array
     *
     * @param int $offset
     *
     * @param int|null $length
     * @param bool $preserveKey
     *
     * @return array
     */
    public function slice(int $offset, int $length = null,bool $preserveKey = false): array
    {
        return array_slice($this->data,$offset,$length,$preserveKey);
    }

    /**
     * @param callable $function
     * @param int $flag
     *
     * @return array
     */
    public function filter(callable $function,int $flag = 0): array
    {
        return array_filter($this->data,$function,$flag);
    }

    /**
     * get the total of elements
     *
     * @return int
     */
    public function size()
    {
        return $this->total();
    }
    /**
     * searches the array for a given value and returns the first corresponding key if successful
     *
     * @param string $needle
     * @param bool $strict
     *
     * @return false|int|string
     */
    public function search(string $needle,$strict = true)
    {
        return array_search($needle,$this->data,$strict);
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
       $this->unset($offset);
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