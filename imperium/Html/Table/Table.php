<?php

namespace Imperium\Html\Table {

    use Carbon\Carbon;
    use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\Collection\Collection;
    use Imperium\Exception\Kedavra;

    /**
     *
     * Generation of  a html table
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
         * @var bool
         */
        private $actions = false;

        /**
         * @var string
         */
        private $edit_url_prefix;


        /**
         * @var string
         */
        private $edit_class;
        /**
         * @var string
         */
        private $edit_icon;

        /**
         * @var string
         */
        private $remove_url_prefix;

        /**
         * @var string
         */
        private $remove_class;

        /**
         * @var string
         */
        private $remove_icon;

        /**
         * @var string
         */
        private $primary;

        /**
         * @var string
         */
        private $remove_text;

        /**
         * @var string
         */
        private $edit_text;

        /**
         * @var string
         */
        private $confirm_text;
        /**
         * @var string
         */
        private $before_class;
        /**
         * @var string
         */
        private $thead_class;

        /**
         * @var string
         */
        private $before_content;

        /**
         * @var string
         */
        private $after_content;

        /**
         *
         * The character limit size
         *
         * @var int
         *
         */
        private $limit;
        /**
         * @var bool
         */
        private $edit;

        /**
         * @var bool
         */
        private $use_ago = false;

        /**
         *
         * @param array $columns
         * @param array $data
         * @param string $before_class
         * @param string $thead_class
         * @param $after_content
         * @param $before_content
         */
        public function __construct(array $columns, array $data,string $before_class,string $thead_class,$after_content,$before_content )
        {
            $this->columns = collection($columns);

            $this->data = collection($data);

            $this->before_class = $before_class;

            $this->thead_class = $thead_class;

            $this->after_content = $after_content;

            $this->before_content = $before_content;
        }

        /**
         *
         * Init value
         *
         * @param array $columns
         * @param array $data
         * @param string $before_class
         * @param string $thead_class
         *
         * @param string $before_content
         * @param string $after_content
         * @return Table
         */
        public static function table(array $columns,array $data,string $before_class,string $thead_class,string $before_content,string $after_content): Table
        {
            return new static($columns,$data,$before_class,$thead_class,$after_content,$before_content);
        }


        /**
         *
         * @param string $content
         *
         * @return Table
         */
        public function before_content(string $content): Table
        {
            append($this->html,$content);

            return $this;
        }

        /**
         *
         * @param string $content
         *
         * @return Table
         *
         */
        public function after_content(string $content): Table
        {
            append($this->html, $content);

            return $this;
        }
        /**
         * @param string $edit_text
         * @param string $remove_text
         * @param string $confirm_text
         * @param string $edit_url_prefix
         * @param string $edit_class
         * @param string $edit_icon
         * @param string $remove_url_prefix
         * @param string $remove_class
         * @param string $remove_icon
         *
         * @param string $primary_key
         * @return Table
         */
        public function set_action(string $edit_text,string $remove_text,string $confirm_text,string $edit_url_prefix, string $edit_class, string $edit_icon, string $remove_url_prefix, string $remove_class, string $remove_icon,string $primary_key): Table
        {
            $this->actions = true;
            $this->edit = true;
            $this->confirm_text = $confirm_text;
            $this->edit_text = $edit_text;
            $this->remove_text = $remove_text;
            $this->edit_url_prefix = $edit_url_prefix;
            $this->edit_class = $edit_class;
            $this->edit_icon = $edit_icon;
            $this->remove_url_prefix = $remove_url_prefix;
            $this->remove_class = $remove_class;
            $this->remove_icon = $remove_icon;
            $this->primary = $primary_key;

            return $this;
        }

        /**
         * @param string $remove_text
         * @param string $confirm_text
         * @param string $remove_url
         * @param string $remove_class
         * @param string $remove_icon
         * @param string $primary_key
         *
         * @return Table
         */
        public function remove_action(string $remove_text,string $confirm_text, string $remove_url,string $remove_class = 'btn btn-danger', string $remove_icon ='<i class="material-icons">close</i>',string $primary_key = 'id'): Table
        {
            $this->actions = true;

            $this->edit = false;

            $this->confirm_text = $confirm_text;
            $this->remove_text = $remove_text;
            $this->remove_url_prefix = $remove_url;

            $this->remove_class = $remove_class;
            $this->remove_icon = $remove_icon;
            $this->primary = $primary_key;

            return $this;
        }

        /**
         *
         * Limit the characters size
         *
         * @param int $size
         *
         * @return Table
         *
         */
        public function limit(int $size): Table
        {
            $this->limit = $size;

            return $this;

        }

        public function use_ago()
        {
            $this->use_ago = true;
            return $this;
        }

        /**
         *
         * Generate the table
         *
         * @param string $class
         *
         * @return string
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         */
        public function generate(string $class = ''): string
        {
            $connected = app()->auth()->connected();
                $this->before_content($this->before_content)->start($class)->start_thead()->start_row();

                foreach ($this->columns->collection() as $column)
                    $this->th($column);

                if ($this->actions)
                {
                    if ($this->edit)
                    {
                        $this->th($this->edit_text)->th($this->remove_text);

                    }else{
                        if ($connected)
                            $this->th($this->remove_text);
                    }

                }



                $this->end_row()->end_thead()->start_tbody();

                foreach ($this->data->collection() as $v)
                {
                    $this->start_row();

                    if (is_object($v))
                    {
                        foreach ($this->columns->collection() as $column)
                        {
                            if ($this->use_ago)
                            {
                                if (has($column,['created_at','update_at']))
                                    $this->td(ago(app()->lang(),$v->$column));
                                elseif (has($column,['finish_at']))
                                {
                                    Carbon::setLocale(app()->lang());
                                    $this->td(Carbon::parse($v->$column)->diffForHumans(null,Carbon::DIFF_RELATIVE_TO_NOW));

                                }else{
                                    $this->td($v->$column);
                                }
                            }
                            else
                            {
                              $this->td($v->$column);
                            }
                        }



                        if ($this->actions && $connected)
                        {
                            $primary = $this->primary;

                            if ($this->edit)
                            {
                                $edit = '<a href="'.$this->edit_url_prefix.'/'.$v->$primary.'" class="'.$this->edit_class.'">'.$this->edit_icon.'</a>';
                                $remove = '<a href="'.$this->remove_url_prefix.'/'.$v->$primary.'" class="'.$this->remove_class.'" data-confirm="'.$this->confirm_text.'" onclick="sure(event,this.attributes[2].value)">'.$this->remove_icon.' </a>';

                                $this->action($edit)->action($remove);
                            }else{

                                $remove = '<a href="'.$this->remove_url_prefix.'/'.$v->$primary.'" class="'.$this->remove_class.'" data-confirm="'.$this->confirm_text.'" onclick="sure(event,this.attributes[2].value)">'.$this->remove_icon.' </a>';

                                $this->action($remove);
                            }

                        }
                    }else {

                        $this->td($v);

                        if ($this->actions && $connected)
                        {

                            if ($this->edit)
                            {
                                $primary = $this->primary;

                                $edit = '<a href="'.$this->edit_url_prefix.'/'.$v[$primary].'" class="'.$this->edit_class.'">'.$this->edit_icon.'</a>';
                                $remove = '<a href="'.$this->remove_url_prefix.'/'.$v[$primary].'" class="'.$this->remove_class.'" data-confirm="'.$this->confirm_text.'" onclick="sure(event,this.attributes[2].value)">'.$this->remove_icon.' </a>';

                                $this->action($edit)->action($remove);
                            }else{

                                $primary = $this->primary;
                                $remove = '<a href="'.$this->remove_url_prefix.'/'.$v[$primary].'" class="'.$this->remove_class.'" data-confirm="'.$this->confirm_text.'" onclick="sure(event,this.attributes[2].value)">'.$this->remove_icon.' </a>';

                                $this->action($remove);
                            }
                        }
                    }

                    $this->end_row();
                }

                return $this->end_tbody()->end()->after_content($this->after_content)->get();

        }

        /**
         * Open the table
         * @param string $class
         * @return Table
         */
        public function start(string $class = ''): Table
        {

            append($this->html,'<div class="'.$this->before_class.'">');

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

            append($this->html,'</div>');

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

            $x = def($this->limit) ? '<td>'.substr($value,0,$this->limit).' </td> '   : '<td>'.$value.'</td>';

            append($this->html,$x);

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
            $x = '<th>'.$value.'</th>';
            append($this->html,$x);

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

        /**
         * @param string $action
         *
         * @return Table
         */
        public function action(string $action): Table
        {
            $x = '<td>'.$action.'</td>';

            append($this->html,$x);

            return $this;
        }
    }
}