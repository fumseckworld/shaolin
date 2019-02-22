<?php

namespace Imperium\Tables {


    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\Connexion\Connect;

    class Column
    {


        /**
         *
         * The column type key
         *
         * @var string
         *
         */
        const TYPE = 'type';

        /**
         *
         * The column size key
         *
         * @var string
         *
         */
        const SIZE = 'size';

        /**
         *
         * The column primary key
         *
         * @var int
         *
         */
        const PRIMARY = 30;

        /**
         *
         * The default value of the column key
         *
         * @var string
         *
         */
        const DEFAULT = 'default';

        /**
         *
         * The columns keys
         *
         * @var string
         *
         */
        const COLUMNS = 'columns';

        /**
         *
         * The table key
         *
         * @var string
         *
         */
        const FOREIGN_TABLE = 'foreign_table';

        /**
         *
         * The index key
         *
         * @var string
         *
         */
        const INDEX = 'index';

        /**
         *
         * The unique key
         *
         * @var string
         *
         */
        const UNIQUE = 'unique';

        /**
         *
         * The column name key
         *
         * @var string
         *
         */
        const COLUMN = 'column';

        /**
         *
         * The constraint key
         *
         * @var string
         *
         */
        const CONSTRAINT = 'constraint';

        /**
         *
         * The mode key
         *
         * @var string
         *
         **/
        const MODE = 'mode';


        /**
         *
         * The check key
         *
         * @var string
         *
         */
        const CHECK = 'check' ;

        /**
         *
         * The after key
         *
         * @var string
         *
         */
        const AFTER = 'after';

        /**
         *
         * The first key
         *
         * @var string
         *
         */
        const FIRST = 'first';

        /**
         *
         * The foreign index key
         *
         * @var string
         *
         */
        const FOREIGN_INDEX = 'foreign_index';

        /**
         *
         * The foreign column key
         *
         * @var string
         *
         */
        const FOREIGN_COLUMN = 'foreign_column';

        /**
         *
         * The action key
         *
         * @var string
         *
         */
        const ACTION = 'action';

        /**
         *
         * All columns added
         *
         * @var string[]
         *
         */
        private $columns;


        /**
         *
         * The column type
         *
         * @var string
         *
         */
        private $type;

        /**
         *
         * The column size
         *
         * @var int
         *
         */
        private $size;

        /**
         *
         * The primary column
         *
         * @var string
         *
         */
        private $primary;

        /**
         *
         * The default value
         *
         * @var mixed
         *
         */
        private $default;

        /**
         *
         * The table name
         *
         * @var string
         *
         */
        private $table;

        /**
         *
         * The index name
         *
         * @var string
         *
         */
        private $index;

        /**
         *
         * The unique column name
         *
         * @var string
         *
         */
        private $unique = false;

        /**
         *
         * The column name
         *
         * @var string
         *
         */
        private $column;

        /**
         *
         * The column constraint
         *
         * @var string[]
         *
         */
        private $constraint;

        /**
         *
         * The mode
         *
         * @var string
         *
         */
        private $mode;

        /**
         *
         * The check constraint
         *
         * @var string
         *
         */
        private $check;

        /**
         *
         * After column name
         *
         * @var string
         *
         */
        private $after;

        /**
         *
         * The first column
         *
         * @var string
         *
         */
        private $first;

        /**
         *
         * The foreign index name
         *
         * @var string
         *
         */
        private $foreign_index;

        /**
         *
         * The foreign column
         *
         * @var string
         *
         */
        private $foreign_column;


        /**
         *
         * The foreign table name
         *
         * @var string
         *
         */
        private $foreign_table;

        /**
         *
         * The on action
         *
         * @var string
         *
         */
        private $action;

        /**
         *
         * The columns defined
         *
         * @var Collection
         *
         */
        private $added;

        /**
         *
         * The connexion
         *
         * @var Connect
         *
         */
        private $connexion;

        /**
         *
         * The current driver
         *
         * @var string
         *
         */
        private $driver;


        /**
         *
         * Column constructor
         *
         * @param Connect $connect
         *
         */
        public function __construct(Connect $connect)
        {
            $this->added = collection();

            $this->connexion = $connect;

            $this->driver  = $connect->driver();

        }

        /**
         *
         * Define the table name
         *
         * @param string $name
         *
         * @return Column
         *
         */
        public function for(string $name): Column
        {
            $this->table = $name;

            return $this;
        }

        /**
         *
         * Start a new column
         *
         * @return Column
         *
         */
        public function column(): Column
        {
            $this->clean();

            return $this;

        }


        /**
         *
         * @return Column
         *
         * @throws Exception
         *
         */
        public function end_column(): Column
        {
            $columns            = $this->get($this->columns);
            $type               = $this->get($this->type);
            $size               = $this->get($this->size);
            $default            = $this->get($this->default);
            $index              = $this->get($this->index);
            $unique             = $this->get($this->unique);
            $column             = $this->get($this->column);
            $constraint         = $this->get($this->constraint);
            $mode               = $this->get($this->mode);
            $check              = $this->get($this->check);
            $after              = $this->get($this->after);
            $first              = $this->get($this->first);
            $foreign_index      = $this->get($this->foreign_index);
            $foreign_column     = $this->get($this->foreign_column);
            $foreign_table      = $this->get($this->foreign_table);
            $action             = $this->get($this->action);

            is_true(not_def($column),true,"The column name is missing");

            $value = [
                self::COLUMNS => $columns,
                self::TYPE => $type,
                self::SIZE => $size,
                self::DEFAULT => $default,
                self::INDEX  => $index,
                self::UNIQUE   => $unique,
                self::CONSTRAINT => $constraint,
                self::MODE => $mode,
                self::CHECK => $check,
                self::AFTER => $after,
                self::FIRST => $first,
                self::FOREIGN_INDEX => $foreign_index,
                self::FOREIGN_TABLE => $foreign_table,
                self::FOREIGN_COLUMN => $foreign_column,
                self::ACTION => $action

            ];

            $this->added->add($value,$column);

            return $this;
        }


        /**
         *
         * Return a string with all columns
         *
         * @param string $glue
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function columns_to_string(string $glue = ', '): string
        {
            return collection($this->show())->join($glue);
        }

        /**
         *
         * Add a check verification
         *
         * @param string $condition
         * @param mixed $expected
         *
         * @return Column
         *
         * @throws Exception
         *
         */
        public function check(string $condition,$expected): Column
        {
            $condition = html_entity_decode($condition);

            $this->check = is_string($expected) ?  'CHECK ('. $this->column  .' '.$condition . $this->connexion->instance()->quote($expected).')' :  "CHECK ({$this->column} $condition $expected)";

            return $this;
        }

        /**
         *
         * Define the column name
         *
         * @param string $name
         *
         * @return Column
         *
         */
        public function name(string $name): Column
        {
            $this->column = $name;

            return $this;
        }

        /**
         *
         * The column type
         *
         * @param string $type
         *
         * @return Column
         *
         */
        public function type(string $type): Column
        {
            $this->type = $type;

            return $this;
        }


        /**
         *
         * Define the mode
         *
         * @param string $mode
         *
         * @return Column
         *
         */
        public function on(string $mode): Column
        {
            $this->mode = $mode;

            return $this;
        }

        /**
         *
         * The column size
         *
         * @param int $size
         *
         * @return Column
         *
         */
        public function size(int $size): Column
        {
           $this->size = $size;

           return $this;
        }

        /**
         *
         * Define the foreign key
         *
         * @param string $table
         * @param string $index_name
         * @param string $column
         *
         * @return Column
         *
         */
        public function foreign(string $table,string $index_name,string $column): Column
        {
            $this->foreign_table    = $table;
            $this->foreign_index    = $index_name;
            $this->foreign_column   = $column;

            return $this;
        }

        /**
         *
         * Define the current column to the primary
         *
         * @return Column
         *
         */
        public function primary(): Column
        {
            $this->primary = $this->column;

            return $this;
        }

        /**
         *
         * Found the primary key
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function primary_key(): string
        {
            switch ($this->driver)
            {
                case MYSQL:

                    foreach ($this->connexion->request("show columns from {$this->current()} where `Key` = 'PRI';") as $key)
                        return $key->Field;
                break;

                case POSTGRESQL:

                    foreach($this->connexion->request ("select column_name FROM information_schema.key_column_usage WHERE table_name = '{$this->current()}';") as $key)
                        return $key->column_name;
                break;

                case SQLITE:

                    foreach ($this->connexion->request("PRAGMA table_info({$this->current()})") as $field)
                    {
                        if (def($field->pk))
                            return $field->name;
                    }
                break;
            }

            throw  new Exception('We have not found a primary key');
        }



        /**
         *
         * Define the default value
         *
         * @param $default
         *
         * @return Column
         *
         */
        public function default($default): Column
        {
            $this->default = $default;

            return $this;
        }

        /**
         *
         * Define the current column to the primary
         *
         * @return Column
         *
         */
        public function unique(): Column
        {
            $this->unique = $this->column;

            return $this;
        }


        /**
         *
         * Define the column name
         *
         * @param string $column
         *
         * @return Column
         *
         */
        public function after(string $column): Column
        {
            $this->after = $column;

            return $this;

        }

        /**
         *
         * Define the action
         *
         * @param string $action
         *
         * @return Column
         *
         */
        public function action(string $action): Column
        {
            $this->action = $action;

            return $this;
        }

        /**
         *
         * Define the first column
         *
         * @param string $column
         *
         * @return Column
         *
         */
        public function first(string $column):Column
        {
            $this->first = $column;

            return $this;

        }

        /**
         *
         * Verify if a column exist
         *
         * @param string $column
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function exist(string $column): bool
        {
            return collection($this->show())->exist($column);
        }

        /**
         *
         * Add a new column in a table
         *
         * @param string $name
         * @param string $type
         * @param int $size
         * @param bool $nullable
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function add(string $name, string $type, int $size,bool $nullable): bool
        {

            $command = "ALTER TABLE {$this->current()} ADD COLUMN ";

            different($size,0) ?  append($command,"$name $type($size) ") :  append($command,"$name $type ");

            if(is_false($nullable))
            {
                $this->connexion->postgresql() ?  append($command,' NOT NULL DEFAULT 0') :  append($command,' NOT NULL');
            }

            return $this->connexion->execute($command);

        }

        /**
         *
         * Return the last column
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function last(): string
        {
            return collection($this->show())->last();
        }


        /**
         *
         * Return the first column
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function begin():string
        {
            return collection($this->show())->begin();
        }

        /**
         *
         * Verify if a column not exist
         *
         * @param string $column
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function not_exist(string $column): bool
        {
            return collection($this->show())->not_exist($column);
        }

        /**
         *
         * Display all columns
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show(): array
        {
            $fields = collection();

            switch ($this->driver)
            {
                case MYSQL :

                    foreach ($this->connexion->request("SHOW FULL COLUMNS FROM {$this->current()}") as $column)
                        $fields->push($column->Field);

                break;

                case POSTGRESQL :

                    foreach ($this->connexion->request("SELECT column_name FROM information_schema.columns WHERE table_name ='{$this->current()}'") as $column)
                        $fields->push($column->column_name);
                break;

                case SQLITE :
                    foreach ($this->connexion->request("PRAGMA table_info({$this->current()})") as $column)
                        $fields->push($column->name);
                break;
            }

            return $fields->collection();
        }


        /**
         *
         * Check if a table has column
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has(): bool
        {
            return def($this->show());
        }

        /**
         *
         * Get columns info
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function info(): array
        {
            $fields = collection();

            switch ($this->driver)
            {
                case MYSQL :
                    foreach ($this->connexion->request("SHOW FULL COLUMNS FROM {$this->current()}") as $column)
                        $fields->push($column);
                break;

                case POSTGRESQL :
                    foreach ($this->connexion->request("SELECT * FROM information_schema.columns WHERE table_name ='{$this->current()}'") as $column)
                        $fields->push($column);
                break;

                case SQLITE :
                    foreach ($this->connexion->request("PRAGMA table_info({$this->current()})") as $column)
                        $fields->push($column);
                break;
            }
            return $fields->collection();
        }

        /**
         *
         * Rename a column
         *
         * @param string $old
         * @param string $new_name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function rename(string $old,string $new_name): bool
        {
           switch ($this->driver)
            {
                case MYSQL :

                    $type = $this->column_type($old);

                    $length = $this->column_length($old);

                    $x =  $length  ?  "($length)" : '';

                    return equal($old,$this->primary_key()) ? false : $this->connexion->execute("ALTER TABLE {$this->current()} CHANGE COLUMN  $old $new_name $type$x ;");
                break;
                case POSTGRESQL :
                case SQLITE :
                    return equal($old,$this->primary_key()) ? false : $this->connexion->execute( "ALTER TABLE {$this->current()} RENAME COLUMN $old TO $new_name;");
                break;
                default:
                    return false;
                break;
            }
        }


        /**
         *
         * Display all types
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function types():array
        {

            $types = collection();

            switch ($this->driver)
            {
                case MYSQL :
                    foreach ($this->connexion->request("SHOW FULL COLUMNS FROM {$this->current()}") as $type)
                    {
                        $x = collection(explode('(', trim($type->Type,')')));
                        $types->push($x->get(0));
                    }
                break;

                case POSTGRESQL :
                    foreach ($this->connexion->request("select data_type FROM information_schema.columns WHERE table_name ='{$this->current()}';") as $type)
                    {
                        $x = collection(explode('(', trim($type->data_type,')')));
                        $types->push($x->get(0));
                    }
                break;

                case SQLITE :
                    foreach ($this->connexion->request("PRAGMA table_info({$this->current()})") as $type)
                    {
                        $x = collection(explode('(', trim($type->type,')')));
                        $types->push($x->get(0));
                    }
                break;
            }

            return $types->collection();

        }

        /**
         *
         * Check if a the current has type
         *
         * @param string $type
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has_type(string $type): bool
        {
            return collection($this->types())->exist($type);
        }

        /**
         *
         * Check if types exist
         *
         * @param string ...$types
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has_types(string ...$types): bool
        {
            foreach ($types as $type)
            {
                if (is_false($this->has_type($type)))
                    return false;
            }

            return true;
        }

        /**
         *
         * Return the column type
         *
         * @param string $column
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function column_type(string $column)
        {
            return collection($this->show())->search($column)->set_new_data($this->types())->result();
        }

        /**
         *
         * Get columns name with types
         *
         * @method get_columns_with_types
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function columns_with_types():array
        {
            $data = collection();

            foreach ($this->show() as $k => $v)
            {
                $data->add($this->column_type($v),$v);
            }
            return $data->collection();
        }


        /**
         *
         * Create the table with all columns
         *
         * @return bool
         *
         */
        public function create(): bool
        {
            foreach ($this->added->collection() as  $column => $args )
            {
                $args = collection($args);
                $check = $args->get(self::CHECK);
                d($check);
            }

        }


        /**
         *
         * Define the index name
         *
         * @param string $name
         *
         * @return Column
         *
         */
        public function index(string $name): Column
        {
            $this->index = $name;

            return $this;
        }


        /**
         *
         * Return the number of all columns found
         *
         * @return int
         *
         * @throws Exception
         *
         */
        public function found(): int
        {
            return collection($this->show())->length();
        }

        /**
         *
         * Define the constraint
         *
         * @param string ...$constraint
         *
         * @return Column
         */
        public function constraint(string ...$constraint): Column
        {

            $this->constraint = $constraint;

            return $this;
        }

        /**
         *
         * Define the columns
         *
         * @param string[] $columns
         *
         * @return Column
         *
         */
        public function set(string ...$columns): Column
        {
            $this->columns = $columns;

            return $this;
        }

        /**
         *
         * Remove the defined columns
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function remove(): bool
        {
            foreach ($this->columns as $column)
                is_false($this->drop($column),true,"The column $column has not been removed");

            return true;

        }



        /**
         *
         * @param $value
         *
         * @return null
         */
        private function get($value)
        {
            return def($value) ? $value : null;
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
            return $this->table;
        }

        /**
         *
         * clean variables
         *
         */
        private function clean(): void
        {
            $required = ['connexion', 'added', 'driver'];

            foreach (get_object_vars($this) as $k => $v)
                assign(!has($k, $required), $this->$k, null);
        }

        /**
         *
         *
         * @param string $column
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function column_length(string $column)
        {
            return collection($this->show())->search($column)->set_new_data($this->columns_length())->result();
        }

        /**
         * @return array
         *
         * @throws Exception
         *
         */
        public function columns_length():array
        {

            $types = collection();

            switch ($this->driver)
            {
                case MYSQL :

                    foreach ($this->connexion->request("SHOW FULL COLUMNS FROM {$this->current()}") as $type)
                    {
                        $x = collection(explode('(', trim($type->Type,')')));

                        $x->has_key(1) ?  $types->push($x->get(1)) : $types->push(0);
                    }

                    break;

                case POSTGRESQL :

                    foreach ($this->connexion->request("select data_type FROM information_schema.columns WHERE table_name =' {$this->current()}';") as $type)
                    {
                        $x = collection(explode('(', trim($type->data_type,')')));
                        $x->has_key(1) ? $types->push($x->get(1)) : $types->push(0);
                    }

                    break;

                case SQLITE :
                    foreach ($this->connexion->request("PRAGMA table_info({$this->current()})") as $type)
                    {
                        $x = collection(explode('(', trim($type->type,')')));
                        $x->has_key(1) ? $types->push($x->get(1)) : $types->push(0);
                    }
                    break;
            }

            return $types->collection();
        }

        /**
         *
         * Remove a column
         *
         * @param string $column
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function drop(string $column): bool
        {
            $primary = $this->primary_key();
            $table = $this->current();

            switch ($this->driver)
            {
                case MYSQL :
                    return equal($column,$primary) ? false :  $this->connexion->execute("ALTER TABLE $table DROP $column");
                break;

                case POSTGRESQL :
                    return  equal($column,$primary) ? false : $this->connexion->execute("ALTER TABLE $table DROP COLUMN $column RESTRICT");
                break;

                default :
                    return false;
                break;

            }
        }
    }
}