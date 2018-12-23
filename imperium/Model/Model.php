<?php


namespace Imperium\Model {

    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\Query\Query;
    use Imperium\Tables\Table;
    use Imperium\Collection\Collection;
    use PDO;

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
         * @var string
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
         * Search in the current table a value
         *
         * @method search
         *
         * @param  mixed $value The value to search
         *
         * @return array
         *
         */
        public function search($value): array
        {
            return $this->sql->mode(Query::SELECT)->like($value)->get();
        }

        /**
         *
         * Display all tables not hidden
         *
         * @method show_tables
         *
         * @param  array      $hidden The hidden table
         *
         * @return array
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
         * @param  string $pagination_prefix_url  [description]
         * @param  array  $hidden_table           [description]
         * @param  string $table_url_prefix       [description]
         * @param  string $url_separator          [description]
         * @param  int    $current_page           [description]
         * @param  int    $limit_records_per_page [description]
         * @param  string $table_class            [description]
         * @param  string $action_remove_text     [description]
         * @param  string $confirm_text           [description]
         * @param  string $remove_btn_class       [description]
         * @param  string $remove_url_prefix      [description]
         * @param  string $remove_icon            [description]
         * @param  string $action_edit_text       [description]
         * @param  string $edit_url_prefix        [description]
         * @param  string $edit_icon              [description]
         * @param  string $edit_btn_class         [description]
         * @param  bool   $align_column           [description]
         * @param  bool   $column_to_upper        [description]
         * @param  bool   $framework              [description]
         * @param  string $start_pagination_text  [description]
         * @param  string $end_pagination_text    [description]
         * @param  string $key                    [description]
         * @param  string $order_by               [description]
         *
         * @return string [description]
         */
        public function show(   string $pagination_prefix_url,array $hidden_table,string $table_url_prefix,
                                string $url_separator,int $current_page,int $limit_records_per_page,
                                string $table_class,string $action_remove_text,string $confirm_text,
                                string $remove_btn_class,string $remove_url_prefix,string $remove_icon,
                                string $action_edit_text,string $edit_url_prefix,string $edit_icon,
                                string $edit_btn_class,bool $align_column,bool $column_to_upper,bool $framework,
                                string $start_pagination_text,string $end_pagination_text,string $key,string $order_by
                            ): string
        {
            $html = '<script>function sure(e,text){if(!confirm(text)){e.preventDefault();}}</script>';

            $records = get_records($this->table,$this->table->get_current_table(),$current_page,$limit_records_per_page,$this->connexion,$framework,$key,$order_by);

            $table_select = tables_select($this->table,$hidden_table,$table_url_prefix,$url_separator);

            $pagination = pagination( $limit_records_per_page,$pagination_prefix_url,$current_page,$this->count(),$start_pagination_text,$end_pagination_text);

            append($html,html('div',$table_select,'mt-2 mb-2'));

            append($html,simply_view($this->table->get_current_table(),$this->table,$this->all(),$table_class,$action_remove_text,$confirm_text,$remove_btn_class,$remove_url_prefix,$remove_icon,$action_edit_text,$edit_url_prefix,$edit_btn_class,$edit_icon,$pagination,$align_column,$column_to_upper));


            return $html;
        }

        /**
         *
         * Change the current table
         *
         * @method change_table
         *
         * @param  string       $table The table name
         *
         * @return Model
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
         * @param  int  $records The number of records
         *
         * @return bool
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
         */
        public function get(): array
        {
            if (not_def($this->column,$this->expected,$this->condition))
                throw new Exception("The where clause was not found");

            return def($this->only) ? $this->sql->mode(Query::SELECT)->columns($this->only)->where($this->param,$this->condition,$this->expected)->get() : $this->sql->mode(Query::SELECT)->where($this->param,$this->condition,$this->expected)->get();
        }


        /**
         *
         * Set a column value
         *
         * @method set
         *
         * @param  string $column_name The column name
         * @param  mixed  $value       The value
         *
         * @return Model 
         *
         */
        public function set(string $column_name,$value): Model
        {
            not_in($this->all_columns,$column_name,true,"The column $column_name was not found in the {$this->current} table");

            equal($column_name,$this->primary,true,"The primary key is already defined");


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

            return $this->insert($data->collection(),$this->current);
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
        public function all(string $order = 'desc'): array
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
            $this->param = $column;
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
         * @param string $table
         * @param array $ignore
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function insert(array $data,string $table ,array $ignore = []): bool
        {
            return $this->table->save($data,$table,$ignore);
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
        public function update(int $id,array $data,string $table,array $ignore =[]): bool
        {
            return $this->table->update($id,$data,$table,$ignore);
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
