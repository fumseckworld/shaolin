<?php

namespace Imperium\Html\Table;


use Imperium\Collection\Collection;

/**
 *
 * Management of the html table
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
class Table
{
    /**
     *
     * The root element
     *
     * @var string
     */
    const ROOT_ELEMENT = 'table';

    /**
     *
     * The Table head element
     *
     * @var string
     *
     */
    const HEAD = 'thead';

    /**
     *
     * @var string
     *
     */
    const HEAD_DATA = 'th';

    /**
     *
     * The body of the table
     *
     * @var string
     *
     */
    const BODY = 'tbody';


    /**
     *
     * The row element
     *
     * @var string
     *
     */
    const ROW = 'tr';

    /**
     * @var string
     */
    const ROW_DATA = 'td';

    /**
     *
     */
    const FOOT_ELEMENT  = 'tfoot';


    /**
     * The  html code
     *
     * @var Collection
     *
     */
    private $data;

    /**
     *
     * @var Collection
     *
     */
    private $columns;

    /**
     *
     * The html code
     *
     * @var string
     */
    private $html = '';

    /**
     * @var int
     */
    private $length;

    /**
     *
     * @param array $columns
     * @param array $data
     */
    public function __construct(array $columns, array $data)
    {
        $this->columns = collection($columns);

        $this->data = collection($data);

        $this->length = $this->data->length();

    }

    /**
     *
     * Init value
     *
     * @param array $columns
     * @param array $data
     *
     * @return Table
     *
     */
    public static function table(array $columns,array $data): Table
    {
        return new static($columns,$data);
    }


    /**
     *
     * Generate the table
     *
     * @param string $class
     *
     * @return string
     *
     */
    public function generate(string $class = ''): string
    {
        $this->start($class)->start_thead()->start_row();

        foreach ($this->columns->collection() as $column)
            $this->th($column);

        $this->end_row()->end_thead()->start_tbody();

        foreach ($this->data->collection() as $v)
        {
            $this->start_row();

            if (is_object($v))
            {
                foreach ($this->columns->collection() as $column)
                    $this->td($v->$column);

            }else {
                $this->td($v);
            }
            $this->end_row();
        }

        return $this->end_row()->end_tbody()->end()->get();
    }

    /**
     * Open the table
     * @param string $class
     * @return Table
     */
    public function start(string $class = ''): Table
    {
        def($class) ?  append($this->html,'<',self::ROOT_ELEMENT, ' class=" '.$class.'" >'): append($this->html,'<',self::ROOT_ELEMENT,'>');

        return $this;
    }

    /**
     *
     * Close the table
     *
     * @return Table
     *
     */
    public function end(): Table
    {
        append($this->html,'</',self::ROOT_ELEMENT,'>');

        return $this;
    }

    /**
     *
     * Open the tfoot
     *
     * @return Table
     *
     */
    public function start_tfoot(): Table
    {
        append($this->html,'<',self::FOOT_ELEMENT,'>');

        return $this;
    }

    /**
     *
     * Close the tfoot
     *
     * @return Table
     *
     */
    public function end_tfoot(): Table
    {
        append($this->html,'</',self::FOOT_ELEMENT,'>');

        return $this;
    }

    /**
     *
     * Open the thead
     *
     * @return Table
     *
     */
    public function start_thead(): Table
    {
        append($this->html,'<',self::HEAD,'>');

        return $this;
    }

    /**
     *
     * Close the thead
     *
     * @return Table
     *
     */
    public function end_thead(): Table
    {
        append($this->html,'</',self::HEAD,'>');

        return $this;
    }

    /**
     *
     * Open the tbody
     *
     * @return Table
     *
     */
    public function start_tbody(): Table
    {
        append($this->html,'<',self::BODY,'>');

        return $this;
    }


    /**
     *
     * Close the tbody
     *
     * @return Table
     *
     */
    public function end_tbody(): Table
    {
        append($this->html,'</',self::BODY,'>');

        return $this;
    }

    /**
     *
     * Generate a td
     *
     * @param mixed $value
     *
     * @return Table
     *
     */
    public function td($value): Table
    {
        append($this->html,'<',self::ROW_DATA,'>');

        append($this->html,$value,'<',self::ROW_DATA,'</>');

        return $this;
    }

    /**
     *
     * Generate a th
     *
     * @param $value
     *
     * @return Table
     *
     */
    public function th($value): Table
    {
        append($this->html,'<',self::HEAD_DATA,'>');

        append($this->html,$value,'<',self::HEAD_DATA,'</>');

        return $this;
    }

    /**
     *
     * Open a row
     *
     * @return Table
     *
     */
    public function start_row(): Table
    {
        append($this->html,'<',self::ROW,'>');

        return $this;
    }


    /**
     *
     * Close the row
     *
     * @return Table
     *
     */
    public function end_row(): Table
    {
        append($this->html,'</',self::ROW,'>');

        return $this;
    }


    /**
     * Get the table
     *
     * @return string
     *
     */
    public function get(): string
    {
        return $this->html;
    }
}