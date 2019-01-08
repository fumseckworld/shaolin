<?php


namespace Imperium\Model {

    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\Query\Query;
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
         * The primary key
         *
         * @var string
         *
         */
        private $primary;

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
         * @var array
         *
         */
        private $only;

        /**
         *
         * All columns found in the current table
         *
         * @var array
         *
         */
        private $all_columns;

        /**
         *
         * @var Collection
         *
         */
        private $data;

        /**
         *
         * All columns with her type
         *
         * @var array
         *
         */
        private $check;

        /**
         *
         *
         * @method __construct
         *
         * @param  Connect     $connect            The connection to the base
         * @param  Table       $table              The instance of table
         * @param  string      $current_table_name The table to manage
         *
         * @throws Exception
         *
         */
        public function __construct(Connect $connect,Table $table, string $current_table_name)
        {
            $this->connexion = $connect;
            $this->table = $table->from($current_table_name);
            $this->all_columns = $this->table->columns();
            $this->primary = $this->table->primary_key();
            $this->data = collection()->add('null',$this->primary);
            $this->sql = query($table,$connect)->from($current_table_name);
            $this->current  = $current_table_name;
            $this->check = $this->table->get_columns_with_types();
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
        public function dump(string $table = ''): bool
        {
            return def($table) ? dumper($this->connexion, false,$table) : dumper($this->connexion,true,'');
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
         * Import the sql file content in the base
         *
         * @method import
         *
         * @param string $sql_file_path The sql file
         * @param  string $base The base name
         *
         * @return bool
         *
         * @throws Exception
         */
        public function import(string $sql_file_path, string $base = ''): bool
        {
            return (new Import($this->connexion, $sql_file_path,$base))->import();
        }

        /**
         *
         * Return the primary key
         *
         * @method primary
         *
         * @return string
         *
         */
        public function primary(): string
        {
            return $this->primary;
        }

        /**
         *
         * Generate a form to update a record
         *
         * @method edit
         *
         * @param  int $id The record id
         * @param  string $action The form action
         * @param  string $form_id The form id
         * @param  string $submit_text The submit text
         * @param  string $submit_class The submit button class
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function edit(int $id,string $action,string $form_id,string $submit_text,string $submit_class): string
        {
            return form($action,$form_id)->generate(2,$this->current,$this->table,$submit_text,$submit_class,'','',Form::EDIT,$id);
        }

        /**
         *
         * Generate a form to create a record
         *
         * @method create
         *
         * @param  string $action The form action
         * @param  string $form_id The form id
         * @param  string $submit_text The submit text
         * @param  string $submit_class The submit class
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function create(string $action,string $form_id,string $submit_text,string $submit_class): string
        {
            return form($action,$form_id)->generate(2,$this->current,$this->table,$submit_text,$submit_class,'','');
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
        public function search(string $value,bool $json_output = false,string $filename = 'search.json')
        {
            return $json_output ? collection($this->sql->mode(Query::SELECT)->like($value)->get())->convert_to_json($filename) : $this->sql->mode(Query::SELECT)->like($value)->get();
        }

        /**
         *
         * Display all tables not hidden
         *
         * @method show_tables
         *
         * @param  array $hidden The hidden table
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show_tables(array $hidden = []): array
        {
            return $this->table->hidden($hidden)->show();
        }

        /**
         *
         * Display records in a table
         *
         * @method show
         *
         * @param string $url_prefix
         * @param  int $current_page
         * @param  int $limit_records_per_page
         * @param  string $table_class
         * @param  string $action_remove_text
         * @param  string $confirm_text
         * @param  string $remove_btn_class
         * @param  string $remove_url_prefix
         * @param  string $remove_icon
         * @param  string $action_edit_text
         * @param  string $edit_url_prefix
         * @param  string $edit_icon
         * @param  string $edit_btn_class
         * @param  bool $align_column
         * @param  bool $column_to_upper
         * @param  string $start_pagination_text
         * @param  string $end_pagination_text
         * @param  string $key
         * @param  string $order_by
         * @param string $search_placeholder
         * @param int $pagination_step
         * @param string $csrf
         *
         * @param bool $pagination_to_right
         * @return string
         *
         * @throws Exception
         */
        public function show(string $url_prefix,int $current_page,int $limit_records_per_page,
                                string $table_class,string $action_remove_text,string $confirm_text,
                                string $remove_btn_class,string $remove_url_prefix,string $remove_icon,
                                string $action_edit_text,string $edit_url_prefix,string $edit_icon,
                                string $edit_btn_class,bool $align_column,bool $column_to_upper,
                                string $start_pagination_text,string $end_pagination_text,string $key,string $order_by,string $search_placeholder, int $pagination_step= 10,$csrf = '',bool $pagination_to_right = true
                            ): string
        {
            inferior($limit_records_per_page,0,true,'The limit records must be superior to 0');

            $table = def(get('table')) ? get('table') : $this->current;

            $url_separator = '=';

            $html = '<script>function sure(e,text){if(!confirm(text)){e.preventDefault();}}</script>';

            $current_page = def(get('current')) ? get('current') : $current_page;

            $records = get_records($this->table,$table,$current_page,$limit_records_per_page,$this->connexion,$key,$order_by);

            $table_select = tables_select($table,$this->table,$this->table->hidden_tables(),$url_prefix,$csrf,$url_separator);

            $pagination = pagination($limit_records_per_page,"$url_prefix$url_separator$table&current=",$current_page,$this->count(),$start_pagination_text,$end_pagination_text);

            append($html,'<div class="row">');
            append($html,html('div',$table_select,'col mr-5  mt-5'));
            append($html,html('div','<input class="form-control" type="number" value="'.$limit_records_per_page.'"  data-url="/"  min="'.$limit_records_per_page.'" step="'.$pagination_step.'" onchange="location = this.attributes[3].value + this.value"','col  ml-5 mt-5'));
            append($html,'</div></div>');
            append($html,'<input placeholder="'.$search_placeholder.'"  class="form-control" onchange="location = this.attributes[3].value + this.value"  data-url="'.$url_prefix.$url_separator.$table.'&q='.'" value="" autofocus="autofocus" type="text">');

            append($html,simply_view($table,$this->table,$records,$table_class,$action_remove_text,$confirm_text,$remove_btn_class,$remove_url_prefix,$remove_icon,$action_edit_text,$edit_url_prefix,$edit_btn_class,$edit_icon,$pagination,$align_column,$column_to_upper,$pagination_to_right));


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
            return new static($this->connexion,$this->table,$table);
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
         *
         */
        public function seed(int $records): bool
        {
            return $this->table->seed($records);
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
            $this->only = $columns;

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

            return def($this->only) ? $this->sql->mode(Query::SELECT)->columns($this->only)->where($this->column,$this->condition,$this->expected)->get() : $this->sql->mode(Query::SELECT)->where($this->column,$this->condition,$this->expected)->get();
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
         * @throws Exception
         *
         */
        public function set(string $column_name,$value): Model
        {
            not_in($this->all_columns,$column_name,true,"The column $column_name was not found in the {$this->current} table");

            equal($column_name,$this->primary,true,"The primary key is already defined");

            is_true(has(collection($this->check)->get($column_name),Table::TEXT_TYPES,false) && ! is_string($value),true,"The value must be a string for the column $column_name");

            is_true(has(collection($this->check)->get($column_name),Table::NUMERIC_TYPES,false) && ! is_numeric($value),true,"The Value must be numeric for the column $column_name");

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
            different(length($this->columns()),length($this->data->collection()),true,"You have missing values");

            $data = collection();

            foreach ($this->columns() as  $column)
                $data->add($this->data->get($column),$column);

            return $this->insert($data->collection());
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
         * Get the news records with a limit and order by clause
         *
         * @param string $order_column
         * @param int $limit
         * @param int $offset
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function news(string $order_column,int $limit,int $offset = 0): array
        {
            return $this->sql->mode(Query::SELECT)->limit($limit,$offset)->order_by($order_column)->get();
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
            return $this->sql->mode(Query::SELECT)->limit($limit,$offset)->order_by($order_column,Table::ASC)->get();
        }

        /**
         *
         * Get all records in current table
         * with an order by
         *
         * @param string $order
         * @return array
         *
         * @throws Exception
         *
         */
        public function all(string $order = Table::DESC): array
        {
            return $this->table->all($order);
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
         *
         */
        public function find(int $id): array
        {
           return $this->sql->mode(Query::SELECT)->where($this->primary,Query::EQUAL,$id)->get();
        }

        /**
         *
         * Find a record or fail if not found
         *
         * @method find_or_fail
         *
         * @param  int          $id The record id
         *
         * @return array
         *
         * @throws Exception
         *
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
         *
         */
        public function remove(int $id): bool
        {
            return $this->sql->mode(Query::DELETE)->where($this->primary,Query::EQUAL,$id)->delete();
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
         *
         */
        public function insert(array $data,array $ignore = []): bool
        {
            return $this->table->save($data,$this->current,$ignore);
        }

        /**
         *
         * Return number of record inside the current table
         *
         * @return int
         *
         * @throws Exception
         *
         */
        public function count(): int
        {
            return $this->table->count($this->current);
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
            return $this->table->found();
        }

        /**
         *
         * Empty the table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function truncate(): bool
        {
            return $this->table->truncate($this->current);
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
        public function update(int $id,array $data,array $ignore =[]): bool
        {
            return $this->table->update($id,$data,$this->current,$ignore);
        }

        /**
         * Display all columns inside the current table
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function columns(): array
        {
            return $this->all_columns;
        }

        /**
         * Check if the current table has not record
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function is_empty(): bool
        {
            return $this->table->is_empty();
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
