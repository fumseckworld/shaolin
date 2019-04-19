<?php


namespace Imperium\Model {

    use Exception;
    use Imperium\App;
    use Imperium\Connexion\Connect;
    use Imperium\Query\Query;
    use Imperium\Request\Request;
    use Imperium\Security\Csrf\Csrf;
    use Imperium\Tables\Table;
    use Imperium\Collection\Collection;
    use Imperium\Html\Form\Form;
    use PDO;
    use Imperium\Import\Import;

    /**
    *
    * Management of the tables
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
    class Model
    {
        /**
         *
         * The connection to the base
         *
         * @var Connect
         *
         */
        private $connexion;

        /**
         *
         * The table management
         *
         * @var Table
         *
         */
        private $table;

        /**
         *
         * The current table
         *
         * @var string
         *
         */
        private $current;

        /**
         *
         * The queries management instance
         *
         * @var Query
         *
         */
        private $sql;


        /**
         *
         * The column used
         *
         * @var string
         *
         */
        private $column;

        /**
         *
         * The where condition
         *
         * @var string
         *
         */
        private $condition;

        /**
         *
         * The expected value
         *
         * @var mixed
         *
         */
        private $expected;

        /**
         *
         * The selected columns
         *
         * @var string
         *
         */
        private $only;

        /**
         *
         * @var Collection
         *
         */
        private $data;

        /**
         *
         *
         * @method __construct
         *
         * @param  Connect     $connect            The connection to the base
         * @param  Table       $table              The instance of table
         *
         * @throws Exception
         *
         */
        public function __construct(Connect $connect,Table $table)
        {
            $this->connexion = $connect;
            $this->table = $table;
            $this->data = collection();
            $this->sql = query($table,$connect);

        }

        /**
         *
         * Dump a table or the base
         *
         * @method dump
         *
         * @param string ...$tables
         * @return bool
         *
         * @throws Exception
         */
        public function dump(string ...$tables): bool
        {
            return dumper( false,$tables);
        }

        /**
         *
         * Dump the base
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function dump_base(): bool
        {
            return dumper(true,[]);
        }

        /**
         *
         * Select a table
         *
         * @param string $table
         *
         * @return Model
         */
        public function from(string $table): Model
        {
            $this->current = $table;

            return $this;
        }


        /**
         *
         * Return the current table
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function current(): string
        {
            is_true(not_def($this->current),true,"No table selected");

            return $this->current;
        }

        /**
         *
         * Update a record
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function update(): bool
        {
            $table = Request::get('__table__');

            $id = intval(Request::get($this->from($table)->primary()));

            $data = collection(Request::all())->remove(Csrf::KEY)->remove('__table__');

            $columns = $this->table()->column()->for($table)->show();

            $x = collection();
            foreach ($columns as $column)
                $x->add($data->get($column),$column);

            return $this->table()->from($table)->update($id,$x->collection());
        }

        /**
         *
         * Import the sql file content in the base
         *
         * @method import
         *
         * @param  string $base The base name
         *
         * @return bool
         *
         * @throws Exception
         */
        public function import(string $base = ''): bool
        {
            return (new Import($base))->import();
        }

        /**
         *
         * Return the primary key
         *
         * @method primary
         *
         * @return string
         *
         * @throws Exception
         */
        public function primary(): string
        {
            return $this->table()->column()->for($this->current())->primary_key();
        }

        /**
         *
         * Generate a form to update a record
         *
         * @method edit
         *
         * @param string $table
         * @param int $id The record id
         * @param string $action The form action
         * @param string $form_id The form id
         * @param string $submit_text The submit text
         * @param string $submit_icon
         * @return string
         *
         * @throws Exception
         */
        public function edit_form(string $table,int $id,string $action,string $form_id,string $submit_text,string $submit_icon): string
        {
            return form($action,id())->generate(2,$table,$submit_text,$form_id,$submit_icon,Form::EDIT,$id);
        }

        /**
         *
         * Generate a form to create a record
         *
         * @method create
         *
         * @param string $table
         * @param  string $action The form action
         * @param  string $form_id The form id
         * @param  string $submit_text The submit text
         * @param string $submit_icon
         * @return string
         *
         * @throws Exception
         */
        public function create_form(string $table,string $action,string $form_id,string $submit_text,string $submit_icon): string
        {
            return form($action,'')->generate(2,$table,$submit_text,$form_id,$submit_icon);
        }

        /**
         *
         * Find a record by a column
         *
         * @param string $column
         * @param mixed $expected
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function by(string $column,$expected): array
        {
            return $this->from($this->current())->where($column,EQUAL,$expected)->get();
        }

        /**
         *
         * Display records without action
         *
         * @param int $mode
         * @param string $url_prefix
         * @param string $column
         * @param string $start_pagination_text
         * @param string $end_pagination_text
         * @param string $expected
         * @param string $identifier_column
         * @return string
         *
         * @throws Exception
         */
        public function display(int $mode,string $url_prefix,string $column,string $start_pagination_text,string $end_pagination_text,string $expected = '',string $identifier_column =''):string
        {

            is_true(not_in(App::DISPLAY_MODE,$mode),true,"The mode is not valid");

            $table = equal($mode,DISPLAY_CONTRIBUTORS) ? $this->current : current_table();

            $current_page =  get('current',1);

            $limit_records_per_page = different($mode,DISPLAY_ARTICLE)  ? get('limit',10) : 12;

            $records = get_records($table,$current_page,$limit_records_per_page,$identifier_column,$expected);

            $pagination = different($url_prefix,'/') ?
                pagination($limit_records_per_page,"$url_prefix?current=",$current_page,$this->count($table),$start_pagination_text,$end_pagination_text)
            : pagination($limit_records_per_page,"?current=",$current_page,$this->count($table),$start_pagination_text,$end_pagination_text);


            switch ($mode)
            {
                case DISPLAY_TABLE:
                    return '<div class="mt-5 mb-5"> <input class="form-control" onchange="location = this.attributes[2].value +this.value"  data-url="?current='. $current_page .'&limit='.'" value="'.get('limit',10).'"  step="10" min="1" type="number"></div>'.\Imperium\Html\Table\Table::table($this->table()->column()->for($table)->show(),$records,'table-responsive','','','')->generate(\collection(config('form','class'))->get('table')). html('div',$pagination,'mt-4');
                break;
                case DISPLAY_ARTICLE:
                    return article($records,$pagination);
                break;
            }
        }

        /**
         *
         * Search in the current table a value
         *
         * @method search
         *
         * @param string $value The value to search
         * @param bool $json_output To save data in a json file
         *
         * @return array|string
         *
         * @throws Exception
         */
        public function search(string $value,bool $json_output = false)
        {
            return $json_output ? collection($this->query()->from($this->current())->mode(Query::SELECT)->like($value)->get())->json() : $this->query()->from($this->current())->mode(Query::SELECT)->like($value)->get();
        }

        /**
         *
         * Display all tables not hidden
         *
         * @method show_tables
         *
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show_tables(): array
        {
            return $this->table->show();
        }

        /**
         * @param string $action_remove_text
         * @param string $confirm_text
         * @param string $action_edit_text
         * @param string $start_pagination_text
         * @param string $end_pagination_text
         * @param string $search_placeholder
         * @param bool $pagination_to_right
         *
         * @return string
         *
         * @throws Exception
         */
        public function show(   string $action_remove_text ='Remove',string $confirm_text = 'Are you sure ?',
                                 string $action_edit_text ='Edit',
                                 string $start_pagination_text= 'Previous',string $end_pagination_text = 'Next',
                                 string $search_placeholder = 'Search',bool $pagination_to_right = true
        ): string
        {
            $url = request()->getRequestUri();
            $table = current_table();

            if(not_def(strstr($url,"?table=$table")))
            {
                $host = \request()->getHost();
                $path = \request()->getPathInfo();

                $url = https() ? "https://$host$path?table=$table" : "http://$host$path?table=$table";
                return to($url);

            }
            $remove_btn_class = \collection(config('form','class'))->get('delete');
            $edit_btn_class = \collection(config('form','class'))->get('edit');
            $session = app()->session();

            $search_icon = fa('fas','fa-search');
            $table_icon = fa('fas','fa-table');
            $pagination_icon = fa('fas','fa-heart');
            $edit_icon = fa('fas','fa-edit');
            $remove_icon = fa('fas','fa-trash-alt');
            $remove_url_prefix =  config('auth','admin_prefix') .'/' . $table .'/remove';
            $edit_url_prefix =  config('auth','admin_prefix') .'/' . $table .'/edit';


            $url_separator = '=';

            $html = '<script>function sure(e,text){if(!confirm(text)){e.preventDefault();}}</script>';

            $current_page = get('current',1);
            $key = get('column','');

            $url_prefix = '?table';


            if (is_false($session->has('limit')))
                $session->set('limit',10);

            $limit_records_per_page = intval($session->get('limit'));

            $records = get_records($table,$current_page,$limit_records_per_page,$key,get('order',DESC));


            $pagination = pagination($limit_records_per_page,"$url_prefix$url_separator$table&current=",$current_page,$this->count($table),$start_pagination_text,$end_pagination_text);

            $primary =  $this->from($table)->primary();

            $tables = collection(['/' => $table]);


            $columns = collection(['/' => get('column',$primary)]);

            $order = collection(['/' => get('order',ASC)]);

            foreach ($this->show_tables() as $x)
            {
                if (different($x,$table))
                    $tables->merge(["?table=$x" => $x]);
            }

            $x = strstr($url,'&column=');
            $url = str_replace($x, "", $url);
            foreach ($this->from($table)->columns() as $x)
            {
                if ($columns->not_exist($x))
                    $columns->merge(["$url&column=$x" => $x]);
            }
            $x = strstr($url,'&order=');
            $url = str_replace($x, "", $url);
            foreach ([DESC,ASC] as $x)
            {
                if ($order->not_exist($x))
                    $order->merge(["$url&order=$x" => $x]);
            }


            $form =  form('',id())->row()->search($search_placeholder,$search_icon)->end_row_and_new()->redirect('table',$tables->collection(),$table_icon)->redirect('column',$columns->collection(),$pagination_icon)->redirect('order',$order->collection(),$pagination_icon)->pagination($pagination_icon,'/pagination/')->end_row()->get();

            append($html,html('div',$form,'container'));

            append($html,simply_view('container','',$table,$records,'table table-bordered table-striped',$action_remove_text,$confirm_text,$remove_btn_class,$remove_url_prefix,$remove_icon,$action_edit_text,$edit_url_prefix,$edit_btn_class,$edit_icon,$pagination,$pagination_to_right));


            return $html;
        }

        /**
         *
         * Change the current table
         *
         * @method change_table
         *
         * @param  string $table The table name
         *
         * @return Model
         *
         * @throws Exception
         *
         */
        public function change_table(string $table): Model
        {
            return $this->from($table);
        }


        /**
         *
         * Check the current driver is mysql
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function is_mysql()
        {
            return $this->connexion->mysql();
        }

        /**
         *
         * Seed the current table
         *
         * @method seed
         *
         * @param  int $records The number of records
         *
         * @return bool
         *
         * @throws Exception
         */
        public function seed(int $records): bool
        {
            return $this->table()->from($this->current())->seed($records);
        }

        /**
         *
         * Select only the columns
         *
         * @method only
         *
         * @param  string[] $columns The columns name
         *
         * @return Model
         *
         */
        public function only(string ...$columns): Model
        {
            $this->only = collection($columns)->join(', ');

            return $this;
        }

        /**
         *
         * Return the query result
         *
         * @method get
         *
         * @return array
         *
         * @throws Exception
         */
        public function get(): array
        {
            is_true(not_def($this->column,$this->expected,$this->condition),true,"The where clause was not found");

            return def($this->only) ? $this->query()->from($this->current())->mode(Query::SELECT)->where($this->column,$this->condition,$this->expected)->only($this->only)->get() : $this->query()->from($this->current())->mode(Query::SELECT)->where($this->column,$this->condition,$this->expected)->get();
        }


        /**
         *
         * Set a column value
         *
         * @method set
         *
         * @param  string $column_name The column name
         * @param  mixed $value The value
         *
         * @return Model
         *
         */
        public function set(string $column_name,$value): Model
        {
            $this->data->add($value,$column_name);

            return $this;
        }

        /**
         *
         * Save new record in the table
         *
         * @return bool
         *
         * @throws Exception
         */
        public function save(): bool
        {
            $data = collection();

            foreach ($this->columns() as  $column)
                $data->add($this->data->get($column),$column);

            return $this->insert_new_record($data->collection());
        }
        /**
         *
         * Return true if the current connexion is postgresql
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function is_postgresql()
        {
            return $this->connexion->postgresql();
        }

        /**
         *
         * Return true if the current connexion is sqlite
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function is_sqlite()
        {
            return $this->connexion->sqlite();
        }

        /**
         *
         * Return the sql builder instance
         *
         * @return Query
         *
         */
        public function query(): Query
        {
            return $this->sql;
        }

        /**
         *
         * @return Table
         *
         * @throws Exception
         */
        public function table(): Table
        {
            return $this->table;
        }


        /**
         *
         * Get the news records with a limit and order by clause
         *
         * @param string $order_column
         * @param int $limit
         * @param int $offset
         *
         * @return array
         *
         * @throws Exception
         */
        public function news(string $order_column,int $limit,int $offset = 0): array
        {
            return $this->query()->from($this->current())->mode(Query::SELECT)->limit($limit,$offset)->order_by($order_column)->get();
        }

        /**
         *
         * Get the lasts record by a limit and an order by clause
         *
         * @param string $order_column
         * @param int $limit
         * @param int $offset
         *
         * @return array
         *
         * @throws Exception
         */
        public function last(string $order_column,int $limit,int $offset = 0): array
        {
            return $this->query()->from($this->current())->mode(Query::SELECT)->limit($limit,$offset)->order_by($order_column,ASC)->get();
        }

        /**
         *
         * Get all records in current table
         * with an order by
         *
         * @param string $column
         * @param string $order
         * @return array
         *
         * @throws Exception
         */
        public function all(string $column = '',string $order = DESC): array
        {
            return def($column) ? $this->table()->from($this->current())->all($column,$order) : $this->table()->from($this->current())->all($this->primary(),$order);
        }

        /**
         *
         * Select a record by this id
         *
         * @param int $id
         *
         * @return object
         *
         * @throws Exception
         */
        public function find(int $id)
        {
            return $this->query()->from($this->current())->mode(Query::SELECT)->where($this->primary(),EQUAL,$id)->use_fetch()->get();
        }

        /**object
         *
         * Find a record or fail if not found
         *
         * @method find_or_fail
         *
         * @param int $id The record id
         *
         * @return object
         *
         * @throws Exception
         */
        public function find_or_fail(int $id)
        {
            $data = $this->find($id);

            is_true(not_def($data),true,'Record was not found');

            return $data;
        }


        /**
         *
         * Select record by a where clause
         *
         * @method where
         *
         * @param  string $column    The column name
         * @param  string $condition The condition
         * @param  mixed  $expected  The expected value
         *
         * @return Model
         *
         */
        public function where(string $column,string $condition,$expected): Model
        {
            $this->column = $column;

            $this->condition = $condition;

            $this->expected = $expected;

            return $this;
        }


        /**
         *
         * Remove a record by this id
         *
         * @param int $id
         *
         * @return bool
         *
         * @throws Exception
         */
        public function remove(int $id): bool
        {
            return $this->query()->from($this->current())->mode(Query::DELETE)->where($this->primary(),Query::EQUAL,$id)->delete();
        }

        /**
         *
         * Insert data in the table
         *
         * @param array $data
         *
         * @return bool
         *
         * @throws Exception
         */
        public function insert_new_record(array $data): bool
        {
            return $this->table()->from($this->current())->save($data);
        }

        /**
         *
         * Insert data in the current table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function add(): bool
        {
            $data = collection(Request::all())->remove(Csrf::KEY)->remove('__table__');

            $primary = $this->table()->column()->for(Request::get('__table__'))->primary_key();
            $columns = $this->from(Request::get('__table__'))->columns();

            $x = collection();

            foreach ($columns as $column)
            {
                if (equal($column, $primary))
                    $x->add($column, $column);
                else
                    $x->add($data->get($column), $column);
            }

            return  $this->table()->from(Request::get('__table__'))->save($x->collection());
        }

        /**
         *
         * Return number of record inside the current table
         *
         * @param string $table
         * @return int
         *
         * @throws Exception
         */
        public function count(string $table): int
        {
            return $this->table()->from($table)->count();
        }


        /**
         *
         * Escape a string value
         *
         * @param string $value
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function quote(string $value): string
        {
            return $this->connexion->instance()->quote($value);
        }

        /**
         *
         * Return the number of all tables found
         *
         * @return int
         *
         * @throws Exception
         *
         */
        public function found(): int
        {
            return $this->table()->found();
        }

        /**
         *
         * Empty the table
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function truncate(string $table): bool
        {
            return $this->table()->truncate($table);
        }


        /**
         *
         * Update a record by this id
         *
         * @param int $id
         * @param array $data
         * @param array $ignore
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function update_record(int $id,array $data,array $ignore =[]): bool
        {
            return $this->table()->from($this->current())->update($id,$data,$ignore);
        }

        /**
         * Display all columns inside the current table
         *
         * @return array
         *
         * @throws Exception
         */
        public function columns(): array
        {
            return $this->table()->column()->for($this->current())->show();
        }

        /**
         *
         * Check if the current table has not record
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function is_empty(string $table): bool
        {
            return $this->table()->from($table)->is_empty();
        }

        /**
         * get pdo instance
         *
         * @return PDO
         *
         * @throws Exception
         *
         */
        public function pdo(): PDO
        {
            return $this->connexion->instance();
        }

        /**
         *
         * return the results of the custom query in an array
         *
         * @param string $query
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function request(string $query): array
        {
            return $this->connexion->request($query);
        }

        /**
         * @param string $request
         *
         * @return object
         *
         * @throws Exception
         */
        public function fetch(string $request)
        {
            return $this->connexion->fetch($request);
        }
        /**
         *
         * Execute a custom query
         *
         * @param string $query
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function execute(string $query): bool
        {
            return $this->connexion->execute($query);
        }


        /**
         *
         * Generate a form by a pdo record
         *
         * @param $record
         * @param string $action
         * @param string $submit_text
         * @param string $table
         * @param string $primary
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function parse($record,string $action,string $submit_text,string $table,string $primary): string
        {

            if (empty($record))
                return back();

            $form = \form($action,id())->row();
            $i = 1;
            foreach ($record as $rec)
            {
                foreach ($rec as $k => $v)
                {
                    if (!is_pair($i))
                    {
                        $form->end_row_and_new();
                    }
                    if (equal($k,$primary))
                        $form->primary($k,$v,$table);
                    else
                        $form->textarea($k,$k,'','','',$v);

                    $i++;
                }
            }
           return $form->end_row_and_new()->submit($submit_text,id())->get();
        }
    }
}
