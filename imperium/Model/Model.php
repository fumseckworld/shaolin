<?php


namespace Imperium\Model {

    use Exception;
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
         * @param  string $table The table name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function dump(string $table): bool
        {
            return dumper( false,$table);
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
            return dumper(true);
        }

        /**
         *
         * Select a table
         *
         * @param string $table
         *
         * @return Model
         *
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
         */
        public function current(): string
        {
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
            $columns = $this->table()->from($table)->columns();

            $x = \collection();
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
            return $this->table()->from($this->current())->primary_key();
        }

        /**
         *
         * Generate a form to update a record
         *
         * @method edit
         *
         * @param string $table
         * @param  int $id The record id
         * @param  string $action The form action
         * @param  string $form_id The form id
         * @param  string $submit_text The submit text
         * @param string $submit_icon
         * @return string
         *
         * @throws Exception
         */
        public function edit_form(string $table,int $id,string $action,string $form_id,string $submit_text,string $submit_icon): string
        {
            return form($action,'')->generate(2,$table,$this->table,$submit_text,$form_id,$submit_icon,Form::EDIT,$id);
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
            return form($action,'')->generate(2,$table,$this->table,$submit_text,$form_id,$submit_icon);
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
         * Search in the current table a value
         *
         * @method search
         *
         * @param  string $value The value to search
         * @param bool $json_output To save data in a json file
         * @param string $filename The json filename
         *
         * @return array|bool
         *
         * @throws Exception
         *
         */
        public function search(string $value,bool $json_output = false, string $filename = 'search.json')
        {
            return $json_output ? collection($this->query()->from($this->current())->mode(Query::SELECT)->like($value)->get())->convert_to_json($filename) : $this->query()->from($this->current())->mode(Query::SELECT)->like($value)->get();
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
         * @param string $container_class
         * @param string $thead_class
         * @param string $url_prefix
         * @param int $current_page
         * @param string $table_class
         * @param string $action_remove_text
         * @param string $confirm_text
         * @param string $remove_icon
         * @param string $remove_url_prfix
         * @param string $action_edit_text
         * @param string $edit_url_prefix
         * @param string $edit_icon
         * @param string $start_pagination_text
         * @param string $end_pagination_text
         * @param string $key
         * @param string $order_by
         * @param string $search_placeholder
         * @param string $table_icon
         * @param string $search_icon
         * @param string $pagination_icon
         * @param bool $pagination_to_right
         * @return string
         * @throws Exception
         */
        public function show(string $container_class,string $thead_class,string $url_prefix,int $current_page,
                                string $table_class,string $action_remove_text,string $confirm_text,
                                string $remove_icon,string $remove_url_prfix,
                                string $action_edit_text,string $edit_url_prefix,string $edit_icon,
                                string $start_pagination_text,string $end_pagination_text,
                                string $key,string $order_by,string $search_placeholder,string $table_icon,string $search_icon,string $pagination_icon,bool $pagination_to_right = true
        ): string
        {


            $remove_btn_class = \collection(config('form','class'))->get('delete');
            $edit_btn_class = \collection(config('form','class'))->get('edit');

            $table = current_table();

            $url_separator = '=';

            $html = '<script>function sure(e,text){if(!confirm(text)){e.preventDefault();}}</script>';

            $current_page = def(get('current')) ? get('current') : $current_page;

            $session = app()->session();

            if (is_false($session->has('limit')))
                $session->set(10,'limit');

            $limit_records_per_page = $session->get('limit');

            $records = get_records($table,$current_page,$limit_records_per_page,$key,$order_by);


            $pagination = pagination($limit_records_per_page,"$url_prefix$url_separator$table&current=",$current_page,$this->count($table),$start_pagination_text,$end_pagination_text);



            $data = collection(['/' => $table]);

            foreach ($this->show_tables() as $x)
            {
                if (different($x,$table))
                    $data->merge(["?table=$x" => $x]);
            }

            $form = \form('/','a')->row()->redirect('table',$data->collection(),$table_icon)->pagination($pagination_icon,'/pagination/')->end_row_and_new()->search($search_placeholder,$search_icon)->end_row()->get();

            append($html,$form);

            append($html,simply_view($container_class,$thead_class,$table,$records,$table_class,$action_remove_text,$confirm_text,$remove_btn_class,$remove_url_prfix,$remove_icon,$action_edit_text,$edit_url_prefix,$edit_btn_class,$edit_icon,$pagination,$pagination_to_right));


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
         *
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
         * @return array
         *
         * @throws Exception
         */
        public function find(int $id): array
        {
            return $this->query()->from($this->current())->mode(Query::SELECT)->where($this->primary(),EQUAL,$id)->get();
        }

        /**
         *
         * Find a record or fail if not found
         *
         * @method find_or_fail
         *
         * @param  int $id The record id
         *
         * @return array
         *
         * @throws Exception
         */
        public function find_or_fail(int $id): array
        {
            $data = $this->find($id);

            inferior($data,1,true,'Record not found');

            superior($data,1,true,"The primary key is not unique");

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
         * @param array $ignore
         *
         * @return bool
         *
         * @throws Exception
         */
        public function insert_new_record(array $data,array $ignore = []): bool
        {
            return $this->table()->from($this->current())->save($data,$ignore);
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

            $columns = $this->from(Request::get('__table__'))->columns();

            $x = collection();

            foreach ($columns as $column)
                $x->add($data->get($column),$column);

            return $this->table()->from(Request::get('__table__'))->save($x->collection());
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
            return $this->table()->from($this->current())->columns();
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
    }
}
