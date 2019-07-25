<?php


namespace Imperium\Model {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\App;
    use Imperium\Connexion\Connect;
    use Imperium\Exception\Kedavra;
    use Imperium\Query\Query;
    use Imperium\Request\Request;
    use Imperium\Routing\Route;
    use Imperium\Security\Csrf\Csrf;
    use Imperium\Tables\Table;
    use Imperium\Collection\Collect;
    use Imperium\Html\Form\Form;
    use Imperium\Zen;
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
    class Model extends Zen
    {

        use Route;

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
         * @var Collect
         *
         */
        private $data;
        /**
         * @var Request
         */
        private $request;

        /**
         *
         *
         * @method __construct
         *
         * @param Connect $connect
         * @param Table $table
         * @param Query $query
         * @param Request $request
         */
        public function __construct(Connect $connect,Table $table,Query $query,Request $request)
        {
            $this->connexion = $connect;
            $this->table = $table;
            $this->data = collect();
            $this->sql = $query;
            $this->request = $request;
        }

        /**
         *
         * Dump a table or the base
         *
         * @method dump
         *
         * @param string ...$tables
         *
         * @return bool
         *
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        public function dump_base(): bool
        {
            return dumper(true);
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
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        public function update(): bool
        {
            $table = $this->request->get('__table__');

            $id = intval($this->request->get($this->from($table)->primary()));

            $data = collect($this->request->all())->del(CSRF::KEY,'__table__');

            $columns = $this->table()->column()->for($table)->show();

            $x = collect();
            foreach ($columns as $column)
                $x->set($data->get($column),$column);

            return $this->table()->from($table)->update($id,$x->all());
        }

        /**
         *
         * Import the sql file content in the base
         *
         * @method import
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function import(): bool
        {
            return (new Import())->import();
        }

        /**
         *
         * Return the primary key
         *
         * @method primary
         *
         * @return string
         *
         * @throws Kedavra
         */
        public function primary(): string
        {
            return $this->table()->from($this->current())->primary();
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
         * @param string $submit_text The submit text
         * @param string $submit_icon
         * @return string
         *
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function edit_form(string $table,int $id,string $action,string $submit_text,string $submit_icon): string
        {
            return form($action,id())->generate(2,$table,$submit_text,$submit_icon,Form::EDIT,$id);
        }

        /**
         *
         * Generate a form to create a record
         *
         * @method create
         *
         * @param string $table
         * @param string $action The form action
         * @param string $submit_text The submit text
         * @param string $submit_icon
         * @return string
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function create_form(string $table,string $action, string $submit_text,string $submit_icon): string
        {
            return form($action,'')->generate(2,$table,$submit_text,$submit_icon);
        }

        /**
         *
         * Find a record by a column
         *
         * @param string $column
         * @param mixed $expected
         *
         * @return object
         *
         * @throws Kedavra
         *
         */
        public function by(string $column,$expected)
        {
            return $this->query()->from($this->current())->mode(SELECT)->where($column,EQUAL,$expected)->use_fetch()->get();
        }

        /**
         *
         * Display records without action
         *
         * @param int $mode
         * @param string $start_pagination_text
         * @param string $end_pagination_text
         * @param string $column
         * @param string $expected
         * @return string
         *
         * @throws Kedavra
         */
        public function display(int $mode,string $start_pagination_text,string $end_pagination_text,string $column ='',string $expected = ''):string
        {

            is_true(not_in(App::DISPLAY_MODE,$mode),true,"The mode is not valid");

            $table =  $this->current();

            $current_page =  get('current',1);

            $limit_records_per_page = different($mode,DISPLAY_ARTICLE)  ? get('limit',10) : 12;

            $records = get_records($table,$current_page,$limit_records_per_page,$column,$expected);

            $pagination = pagination($limit_records_per_page,"?current=",$current_page,$this->count($table),$start_pagination_text,$end_pagination_text);


            switch ($mode)
            {
                case DISPLAY_TABLE:
                    return '<div class="mt-5 mb-5"> <input class="form-control" onchange="location = this.attributes[2].value +this.value"  data-url="?current='. $current_page .'&limit='.'" value="'.get('limit',10).'"  step="10" min="1" type="number"></div>'.\Imperium\Html\Table\Table::table($this->table()->column()->for($table)->show(),$records,'table-responsive','','','')->generate(collect(config('form','class'))->get('table')). html('div',$pagination,'mt-4');
                break;
                case DISPLAY_ARTICLE:
                    return article($records,$pagination);
                break;
                default:
                    return '';
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
         * @throws Kedavra
         */
        public function search(string $value,bool $json_output = false)
        {
            return $json_output ? collect($this->query()->from($this->current())->mode(SELECT)->like($value)->get())->json() : $this->query()->from($this->current())->mode(Query::SELECT)->like($value)->get();
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
         * @throws Kedavra
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
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
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
            $remove_btn_class = \collect(config('form','class'))->get('delete');
            $edit_btn_class = \collect(config('form','class'))->get('edit');
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

            $url_prefix = '?table';


            if (is_false($session->has('limit')))
                $session->put('limit',10);

            $limit_records_per_page = intval($session->get('limit'));
            $primary =  $this->from($table)->primary();
            $records = get_records($table,$current_page,$limit_records_per_page,get('column',$primary));


            $pagination = pagination($limit_records_per_page,"$url_prefix$url_separator$table&current=",$current_page,$this->count($table),$start_pagination_text,$end_pagination_text);



            $tables = collect(['/' => $table]);


            $columns = collect(['/' => get('column',$primary)]);

            $order = collect(['/' => get('order',ASC)]);

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


            $form =  form('',id())->row()->search($search_placeholder,$search_icon)->end_row_and_new()->redirect('table',$tables->all(),$table_icon)->redirect('column',$columns->all(),$pagination_icon)->redirect('order',$order->all(),$pagination_icon)->pagination($pagination_icon,'/pagination/')->end_row()->get();

            append($html,html('div',$form,'container'));

            append($html,simply_view('container','',$table,$records,collect(config('form','class'))->get('table'),$action_remove_text,$confirm_text,$remove_btn_class,$remove_url_prefix,$remove_icon,$action_edit_text,$edit_url_prefix,$edit_btn_class,$edit_icon,$pagination,$pagination_to_right));


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
         * @throws Kedavra
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
         * @throws Kedavra
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
            $this->only = collect($columns)->join();

            return $this;
        }

        /**
         *
         * Return the query result
         *
         * @method get
         *
         * @return mixed
         *
         * @throws Kedavra
         *
         */
        public function get()
        {
            is_true(not_def($this->column,$this->expected,$this->condition),true,"The where clause was not found");

            return def($this->only) ? $this->query()->from($this->current())->mode(SELECT)->where($this->column,$this->condition,$this->expected)->only($this->only)->get() : $this->query()->from($this->current())->mode(Query::SELECT)->where($this->column,$this->condition,$this->expected)->get();
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
            $this->data->put($column_name,$value);

            return $this;
        }

        /**
         *
         * Save new record in the table
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function save(): bool
        {
            $data = collect();

            foreach ($this->columns() as  $column)
                $data->put($column,$this->data->get($column));

            return $this->insert_new_record($this,$data->all());
        }
        /**
         *
         * Return true if the current connexion is postgresql
         *
         * @return bool
         *
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
         */
        public function news(string $order_column,int $limit,int $offset = 0): array
        {
            return $this->query()->from($this->current())->mode(SELECT)->limit($limit,$offset)->order_by($order_column)->get();
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
         * @throws Kedavra
         */
        public function last(string $order_column,int $limit,int $offset = 0): array
        {
            return $this->query()->from($this->current())->mode(SELECT)->limit($limit,$offset)->order_by($order_column,ASC)->get();
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
         * @throws Kedavra
         */
        public function all(string $column = '',string $order = DESC): array
        {
            return def($column) ? $this->table()->from($this->current())->all($column,$order) : $this->table()->from($this->current())->all($this->primary(),$order);
        }

        /**
         *
         * Return a result by a column or fail
         *
         * @param string $column
         * @param $expected
         *
         * @param string $message
         * @return object
         *
         * @throws Kedavra
         */
        public function by_or_fail(string $column,$expected,string $message ='Record was not found'): object
        {
            return exist($this->by($column,$expected),true,$message);
        }

        /**
         *
         * Select a record by this id
         *
         * @param int $id
         *
         * @return object
         *
         * @throws Kedavra
         */
        public function find(int $id)
        {
            return $this->query()->from($this->current())->mode(SELECT)->where($this->primary(),EQUAL,$id)->use_fetch()->get();
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
         * @throws Kedavra
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
         * @throws Kedavra
         */
        public function remove(int $id): bool
        {
            return $this->query()->from($this->current())->mode(Query::DELETE)->where($this->primary(),EQUAL,$id)->delete();
        }

        /**
         *
         * Insert data in the table
         *
         * @param Model $model
         * @param array $data
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function insert_new_record(Model $model,array $data): bool
        {

            return $this->table()->from($this->current())->save($model,$data);
        }

        /**
         *
         * Insert data in the current table
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function add(): bool
        {
            $table = $this->request->get('__table__');
            $data = collect($this->request->all())->del(Csrf::KEY,'__table__');

            $primary = $this->table()->column()->for($table)->primary_key();
            $columns = $this->from($table)->columns();

            $x = collect();

            foreach ($columns as $column)
                equal($column, $primary) ? $x->put($column, $column) : $x->put($column,$data->get($column));

            return  $this->table()->from($table)->save($this,$x->all());
        }

        /**
         *
         * Return number of record inside the current table
         *
         * @param string $table
         * @return int
         *
         * @throws Kedavra
         */
        public function count(string $table = ''): int
        {
            return def($table) ? $this->table()->from($table)->count() : $this->table()->from($this->current())->count();
        }


        /**
         *
         * Escape a string value
         *
         * @param string $value
         *
         * @return string
         *
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @param array $data
         * @return array
         *
         * @throws Kedavra
         */
        public function request(string $query,array $data): array
        {
            return $this->connexion->request($query,$data);
        }

        /**
         * @param string $request
         *
         * @param array $data
         * @return object
         *
         * @throws Kedavra
         */
        public function fetch(string $request,array $data)
        {
            return $this->connexion->fetch($request,$data);
        }

        /**
         *
         * Execute a custom query
         *
         * @param string $query
         *
         * @return bool
         *
         * @throws Kedavra
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
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
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
           return $form->end_row_and_new()->submit($submit_text)->get();
        }
    }
}
