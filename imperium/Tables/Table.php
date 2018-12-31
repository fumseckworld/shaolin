<?php


namespace Imperium\Tables {

    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\Connexion\Connect;
    use Imperium\Import\Import;
    use Imperium\Zen;
    use Imperium\Imperium;

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
    class Table extends Zen
    {
        /**
         *
         * All columns added to create a new table
         *
         * @var Collection
         *
         */
        private $added_columns;

        /**
         * @var Connect
         */
        private $connexion;

        /**
         * Current table
         *
         * @var string
         */
        private $table;

        /**
         *
         * Current driver
         *
         * @var string
         */
        private $driver;

        /**
         * @var array
         */
        private $hidden;

        /**
         * @var string
         */
        private $engine;

        /**
         * @var string
         */
        private $collation;

        /**
         * @var string
         */
        private $charset;

        /**
         *
         * All columns types
         *
         * @var array
         */
        private $all_types;

        /**
         *
         * All columns found in the table
         *
         * @var array
         *
         */
        private $columns;

        /**
         *
         * All columns types found in the table
         *
         * @var array
         *
         */
        private $types;

        /**
         *
         * All tables name
         *
         * @var array
         *
         */
        private $all;

        /**
         *
         * All available collations
         *
         * @var array
         *
         */
        private $all_collation;

        /**
         *
         * All available charsets
         *
         * @var array
         *
         */
        private $all_charset;

        /**
         *
         * The length found in the current table
         *
         * @var array
         *
         */
        private $length;

        /**
         *
         * Return the type of a column
         *
         * @param string $column
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function type(string $column)
        {
            return collection($this->columns())->search($column)->set_new_data($this->columns_types())->result();
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
        public function get_columns_with_types():array
        {
            $data = collection();
            foreach ($this->columns as $k => $v)
            {
                $data->add($this->type($v),$v);
            }
            return $data->collection();
        }

        /**
         *
         * Return the check clause
         *
         * @method check
         *
         * @param  string $column
         * @param  string $condition
         * @param  mixed  $expected
         *
         * @return string
         *
         */
        private function check(string $column,string $condition,$expected): string
        {
            return is_string($expected) ?  "CHECK ($column $condition '$expected')" :  "CHECK ($column $condition $expected)";
        }

        /**
         * Select a table
         *
         * @method from
         *
         * @param  string $table The table to manage
         *
         * @return Table
         *
         * @throws Exception
         *
         */
        public function from(string $table): Table
        {
            $this->table = $table;

            return $this;
        }

        /**
         *
         * @method __construct
         *
         * @param  Connect $connect The connection to the base
         * @param  string $current_table The current table name
         * @param array $hidden The hidden tables
         *
         * @throws Exception
         *
         */
        public function __construct(Connect $connect,string $current_table,array $hidden = [])
        {
            $this->connexion        = $connect;
            $this->driver           = $connect->driver();
            $this->all              = $this->hidden($hidden)->show();
            $this->added_columns    = collection();
            $this->all_types        = collection()->merge(self::DATE_TYPES,self::NUMERIC_TYPES,self::TEXT_TYPES)->collection();
            $this->columns          = $this->from($current_table)->columns();
            $this->types            = $this->from($current_table)->columns_types();
            $this->length           = $this->from($current_table)->columns_length();
            $this->all_collation    = collation($connect);
            $this->all_charset      = charset($connect);
        }


        /**
         *
         * Return the current table name
         *
         * @return string
         *
         */
        public function current(): string
        {
            return  $this->table;
        }

        /**
         *
         * Get columns information
         *
         * @method get_columns_info
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function get_columns_info(): array
        {
            $fields = collection();

            switch ($this->driver)
            {
                case Connect::MYSQL :

                    foreach ($this->connexion->request("SHOW FULL COLUMNS FROM {$this->current()}") as $column)
                        $fields->push($column);
                break;

                case Connect::POSTGRESQL :

                    foreach ($this->connexion->request("SELECT * FROM information_schema.columns WHERE table_name ='{$this->current()}'") as $column)
                        $fields->push($column);

                break;

                case Connect::SQLITE :
                    foreach ($this->connexion->request("PRAGMA table_info({$this->current()})") as $column)
                        $fields->push($column);
                break;
            }
            return $fields->collection();
        }

        /**
         *
         * Check if current database has table
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
         * Change  collation
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function change_collation(): bool
        {
            is_false(def($this->collation),true,"The collation was not define");

            switch ($this->driver)
            {
                case Connect::MYSQL;
                    return $this->connexion->execute("ALTER TABLE {$this->current()} COLLATE {$this->collation};");
                break;
                case Connect::POSTGRESQL:
                    return $this->connexion->execute("update pg_database set datcollate='{$this->collation}', datctype='{$this->collation}' where datname = '{$this->connexion->base()}'");
                break;
                default:
                    return false;
                break;
            }
        }

        /**
         *
         * Change charset
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function change_charset(): bool
        {
            is_false(def($this->charset),true,"The charset was not define");

            switch ($this->driver)
            {
                case Connect::MYSQL;
                    return $this->connexion->execute("ALTER TABLE {$this->current()} CHARACTER SET = {$this->charset};");
                break;
                case Connect::POSTGRESQL:
                    return $this->connexion->execute("update pg_database set encoding = pg_char_to_encoding('{$this->charset}') where datname = '{$this->connexion->base()}'");
                break;
                default:
                    return false;
                break;
            }
        }


        /**
         *
         * Check if a column exist in the current table
         *
         * @method has_column
         *
         * @param  string $column The column name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has_column(string $column): bool
        {
            return collection($this->columns())->exist($column);
        }


        /**
         *
         * Check if a column not exist in the current table
         *
         * @method column_not_exist
         *
         * @param  string $column The column name
         *
         * @return bool
         *
         * @throws Exception
         */
        public function column_not_exist(string $column): bool
        {
            return collection($this->columns)->not_exist($column);
        }

        /**
         *
         * Display all columns inside a table
         *
         * @method get_columns
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function columns(): array
        {
            $fields = collection();

            switch ($this->driver)
            {
                case Connect::MYSQL :

                    foreach ($this->connexion->request("SHOW FULL COLUMNS FROM {$this->current()}") as $column)
                        $fields->push($column->Field);

                break;

                case Connect::POSTGRESQL :

                    foreach ($this->connexion->request("SELECT column_name FROM information_schema.columns WHERE table_name ='{$this->current()}'") as $column)
                         $fields->push($column->column_name);

                break;

                case Connect::SQLITE :
                    foreach ($this->connexion->request("PRAGMA table_info({$this->current()})") as $column)
                        $fields->push($column->name);
                break;
            }

            return $fields->collection();
        }

        /**
         *
         * Remove a table
         *
         * @method drop
         *
         * @param  string $table The table name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function drop(string $table): bool
        {
            return $this->connexion->execute("DROP TABLE $table");
        }

        /**
         *
         * Empty the table
         *
         * @method truncate
         *
         * @param  string $table The table name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function truncate(string $table): bool
        {

            switch ($this->driver)
            {
                case Connect::MYSQL :
                    return $this->connexion->execute("TRUNCATE TABLE $table");
                break;
                case Connect::POSTGRESQL :
                    return $this->connexion->execute("TRUNCATE TABLE $table  RESTART IDENTITY");
                break;
                case Connect::SQLITE :
                    return $this->connexion->execute("DELETE  FROM $table") && $this->connexion->execute('VACUUM');
                break;
                default:
                    return false;
                break;
            }


        }

        /**
         *
         * Append a column in the create table
         *
         * @method column
         *
         * @param  string $type            The field type
         * @param  string $name            The field name
         * @param  bool   $primary         To create a primary key
         * @param  int    $length          The field length
         * @param  bool   $unique          To create a unique field
         * @param  bool   $nullable        To create a nullable or not field
         * @param  bool   $default         To add a default value
         * @param  mixed  $default_value   The default value
         * @param  bool   $check           To add a check clause
         * @param  string $check_condition The check condition
         * @param  mixed $check_expected   The check expected value
         *
         * @return Table
         *
         **/
        public function column(string $type, string $name, bool $primary, int $length, bool $unique, bool $nullable,bool $default,$default_value,bool $check,string $check_condition,$check_expected): Table
        {

            $x = collection()
                ->add($name,self::FIELD_NAME)
                ->add($type,self::FIELD_TYPE)
                ->add($primary, self::FIELD_PRIMARY)
                ->add($length, self::FIELD_LENGTH )
                ->add($unique,self::FIELD_UNIQUE)
                ->add($nullable,self::FIELD_NULLABLE)
                ->add($default,self::DEFAULT)
                ->add($default_value,self::DEFAULT_VALUE)
                ->add($check,self::CHECK)
                ->add($check_condition,self::CHECK_CONDITION)
                ->add($check_expected,self::CHECK_EXPECTED)
            ->collection();

            $this->added_columns->push($x);

            return $this;
        }

        /**
         *
         * Append a column in a existing table
         *
         * @method append_column
         *
         * @param  string $name The column name
         * @param  string $type The column type
         * @param  int $size The column size
         * @param  bool $unique The column unique constraint
         * @param  bool $nullable The column not null constraint
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function append_column(string $name, string $type, int $size, bool $unique,bool $nullable): bool
        {
            if ($this->column_not_exist($name))
            {
                $data = collection();
                $command = "ALTER TABLE {$this->current()} ADD COLUMN ";

                different($size,0) ?  append($command,"$name $type($size) ") :  append($command,"$name $type ");

                if(is_false($nullable))
                    append($command,' NOT NULL');

                $data->add($this->connexion->execute($command));

                if($unique)
                    $data->add($this->alter_table(Imperium::FIELD_UNIQUE,$name));

                return $data->not_exist(false);
            }
            return false;
        }

        /**
         *
         *
         * @method alter_table
         *
         * @param  string $constraint
         * @param  string $column
         * @param  string $table
         *
         * @return bool       
         * @throws Exception
         */
        public function alter_table(string $constraint,string $column,$table = ''): bool
        {
            $table = def($table) ? $table : $this->current();

            if (equal($this->driver,Connect::MYSQL))
            {
                if (equal($constraint,Imperium::FIELD_UNIQUE))
                {
                    return $this->connexion->execute("ALTER TABLE $table ADD  UNIQUE ($column)");
                }
            }

            if (equal($this->driver,Connect::POSTGRESQL))
            {
                if (equal($constraint,Imperium::FIELD_UNIQUE))
                {
                    return $this->connexion->execute("ALTER TABLE $table ADD UNIQUE ($column);");
                }
            }

            if (equal($this->driver,Connect::SQLITE))
            {
                if (equal($constraint,Imperium::FIELD_UNIQUE))
                {
                    return $this->connexion->execute("ALTER TABLE $table  ADD CONSTRAINT _uniq_$column UNIQUE($column);");
                }
            }
            return false;
        }



        /**
         *
         * Remove a constraint in a table
         *
         * @param string $column
         * @param string $constraint
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function remove_constraint(string $column,string $constraint)
        {
            switch ($this->driver)
            {
                case Connect::MYSQL;
                    return  $this->connexion->execute("ALTER TABLE {$this->current()} DROP CONSTRAINT $constraint;");
                break;
                case Connect::SQLITE:
                    return $this->connexion->execute("ALTER TABLE {$this->current()} ALTER COLUMN $column DROP $constraint;");
                break;
                default:
                    return false;
                break;
            }

        }

        /**
         *
         * Create the table
         *
         * @method create
         *
         * @param  string $table The table name
         *
         * @return bool
         *
         * @throws Exception
         *
         **/
        public function create(string $table): bool
        {
            is_true($this->exist($table),true,"The table $table already exist");

            $command = $this->startCreateCommand($table);

            $columns = $this->added_columns;
            $uniq = collection();

            foreach ($columns->collection() as $column)
            {
                $end =  has($column[Imperium::FIELD_NAME],$columns->last());
                if($column[Table::FIELD_UNIQUE]){ $uniq->add($column[Table::FIELD_NAME]);}
                append($command,$this->updateCreateCommand($column,$end));

            }


            append($command, ' )');

            $this->added_columns = collection();

            return $this->connexion->execute($command);
        }


        /**
         *
         * Execute the sql file content
         *
         * @method import
         *
         * @param  string $sql_file
         *
         * @return bool
         *
         * @throws Exception
         *
         **/
        public function import(string $sql_file): bool
        {
            return (new Import($this->connexion,$sql_file))->import();
        }

        /**
         *
         *
         * @method startCreateCommand
         *
         * @param  string             $table
         *
         * @return string
         *
         */
        private function startCreateCommand(string $table ) : string
        {
            return "CREATE TABLE IF NOT EXISTS $table ( ";
        }

        /**
         *
         * Check if the fields is equal to the last field
         *
         * @method is_the_last_field
         *
         * @param  string $field The field name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function is_the_last_field(string $field): bool
        {
            return equal($field,collection($this->columns)->last());
        }

        /**
         *
         * append field in command to create table
         *
         *
         * @param array $field
         *
         * @param bool $end
         *
         * @return string
         *
         * @throws Exception
         *
         */
        private function updateCreateCommand(array $field,bool $end) : string
        {
            $x = collection($field);
            $check = def($x->get(self::CHECK)) ? $this->check($x->get(self::FIELD_NAME), $x->get(self::CHECK_CONDITION), $x->get(self::CHECK_EXPECTED)) :  '';

            $size = $x->get(Imperium::FIELD_LENGTH);

            $command = '';

            append($command,"{$x->get(Imperium::FIELD_NAME)} ");

            $size ? append($command," {$x->get(Imperium::FIELD_TYPE)}($size)") : append($command," {$x->get(Imperium::FIELD_TYPE)} ");



            if ($x->get(Imperium::FIELD_PRIMARY))
            {
                switch ($this->driver)
                {
                    case Connect::MYSQL:
                         append($command,' AUTO_INCREMENT PRIMARY KEY');
                    break;
                    case Connect::POSTGRESQL:
                          append($command,'  PRIMARY KEY');
                    break;
                    case Connect::SQLITE:
                       append($command,'  PRIMARY KEY AUTOINCREMENT');
                    break;
                    }
                }

                if (!$end)
                {
                    if(is_false($x->get(Table::FIELD_NULLABLE)))
                        append($command,' NOT NULL');

                    if(is_true($x->get(Table::DEFAULT)))
                    {
                        $value = $x->get(Table::DEFAULT_VALUE);
                        if(is_string($value))
                            append($command," DEFAULT '$value'");
                        else
                            append($command," DEFAULT $value");
                    }

                }
                append($command," $check");

                if (!$end)
                    append($command,', ');

            return $command;
        }

        /**
         *
         * Seed current table
         *
         * @param int $records
         *
         * @return bool
         *
         * @throws Exception
         */
        public function seed(int $records): bool
        {
            $table = $this->current();
            $columns = $this->from($table)->columns();
            $columns_str = collection($columns)->join(',');

            $query = "INSERT INTO $table ($columns_str) VALUES ";

            $primary = $this->from($table)->primary_key();

            $x = collection();

            $sqlite = $this->connexion->sqlite();

            $types = collection($this->from($table)->columns_types());

            for($i=0;different($i,$records);$i++)
            {
                foreach ($columns as $k => $column)
                {
                    $type = $sqlite ? strtolower($types->get($k)) : $types->get($k);

                    if (equal($column,$primary))
                    {
                        switch ($this->driver)
                        {
                            case Connect::MYSQL:
                            case Connect::SQLITE:
                                $x->add('NULL', $column);
                            break;
                            default:
                                $x->add('DEFAULT',$column);
                            break;
                        }
                    }
                    else
                    {

                        if (has($type, self::BOOL))
                            $x->add(true_or_false(),$column);


                        if (has($type, self::JSONS))
                        {
                            $data = collection();
                            $number = rand(1,10);

                            for ($i=0; $i < $number ; $i++)
                            {
                                if(is_pair($i))
                                    $data->add(faker()->text(50),$i);
                                else
                                    $data->add(faker()->numberBetween(1,50),$i);
                            }
                            $x->add($data->json(),$column);

                        }
                        if (has($type,self::DATE_TYPES))
                            $x->add($this->connexion->instance()->quote(faker()->date()),$column);

                        if (has($type,self::NUMERIC_TYPES))
                            $x->add(faker()->numberBetween(1,100),$column);

                        if (has($type,self::TEXT_TYPES) || not_in(self::ALL_TYPES, $type))
                            $x->add($this->connexion->instance()->quote(faker()->text(50)),$column);
                    }

                }


                $value = '(' .$x->join(', ') . '),';

                append($query,$value);

                $x->clear();
            }
            $query = trim($query,',');

            return $this->connexion->execute($query);

        }

        /**
         *
         * Get all available types for current driver
         *
         * @method types
         *
         * @return array
         *
         */
        public function types() : array
        {
            switch ($this->driver)
            {
                case Connect::MYSQL:
                    return self::MYSQL_TYPES;
                break;
                case Connect::POSTGRESQL:
                    return self::POSTGRESQL_TYPES;
                break;
                case Connect::SQLITE:
                    return self::SQLITE_TYPES;
                break;
                default:
                    return [];
                break;
            }
        }

        /**
         *
         * Set hidden tables
         *
         * @method hidden
         *
         * @param array  $hidden The hidden tables
         *
         * @return Table
         *
         */
        public function hidden(array $hidden) : Table
        {
            $this->hidden = $hidden;

            return $this;
        }

        /**
         *
         * Dump a table
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
            return def($table) ? dumper($this->connexion,false,$table) : dumper($this->connexion,false,$this->current());
        }


        /**
         * @return string
         *
         * @throws Exception
         */
        private function detectPrimaryKey(): string
        {
            switch ($this->driver)
            {
                case Connect::MYSQL:

                    foreach ($this->connexion->request("show columns from {$this->current()} where `Key` = 'PRI';") as $key)
                        return $key->Field;

                break;

                case Connect::POSTGRESQL:

                    foreach($this->connexion->request ("select column_name FROM information_schema.key_column_usage WHERE table_name = '{$this->current()}';") as $key)
                        return $key->column_name;
                break;

                case Connect::SQLITE:

                    foreach ($this->connexion->request("PRAGMA table_info({$this->current()})") as $field)
                    {
                        if (def($field->pk))
                            return $field->name;
                    }

                break;
            }
            return '';
        }

        /**
         *
         * Found the primary key
         *
         * @method primary_key
         *
         * @return string
         *
         * @throws Exception
         *
         */
        public function primary_key(): string
        {
            $primary = $this->detectPrimaryKey();

            is_true(not_def($primary),true,'We have not found a primary key');

            return $primary;

        }


        /**
         *
         * Check if the current table  has not records
         *
         * @method is_empty
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function is_empty(): bool
        {
            return collection($this->all())->empty();
        }

        /**
         *
         * Select a record by this id
         *
         * @method select_by_id
         *
         * @param  int $id the record id
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function select(int $id): array
        {
            return $this->connexion->request("SELECT * FROM {$this->current()} WHERE {$this->primary_key()} = $id" );
        }


        /**
         *
         * Select a record by id or fail
         *
         * @method select_or_fail
         *
         * @param  int                  $id The record id
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function select_or_fail(int $id): array
        {
            $data = $this->connexion->request("SELECT * FROM {$this->current()} WHERE {$this->primary_key()} = $id" );

            superior($data,1,true,"The primary key is not unique");

            inferior($data,1,true,"The records was not found");

            return $data;
        }

        /**
         *
         * Remove a record by id
         *
         * @method remove
         *
         * @param  int $id The record id
         *
         * @return bool
         *
         * @throws Exception
         */
        public function remove(int $id): bool
        {
            return $this->connexion->execute("DELETE FROM {$this->current()} WHERE {$this->primary_key()} = $id");
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
         */
        public function columns_to_string(string $glue = ', '): string
        {
            return collection($this->columns)->join($glue);
        }


        /**
         *
         * Return a string with all columns
         *
         * @param string $old
         * @param string $new
         * @param string $glue
         *
         * @return string
         *
         * @throws Exception
         */
        public function change_columns_name_to_string(string $old,string $new,string $glue): string
        {
            return collection($this->columns)->change_value($old,$new)->join($glue);
        }

        /**
         *
         * Rename a column
         *
         * @method rename_column
         *
         * @param  string $old The old column name
         * @param  string $new The new column name
         *
         * @return bool
         *
         * @throws Exception
         */
        public function rename_column(string $old, string $new): bool
        {

            switch ($this->driver)
            {
                case Connect::MYSQL:

                    $type = $this->type($old);

                    $length = $this->length($old);

                    $x =  $length  ?  "($length)" : '';

                    return equal($old,$this->primary_key()) ? false : $this->connexion->execute("ALTER TABLE {$this->current()} CHANGE COLUMN  $old $new $type$x ;");
                break;
                case Connect::POSTGRESQL:
                case Connect::SQLITE:
                    return equal($old,$this->primary_key()) ? false : $this->connexion->execute( "ALTER TABLE {$this->current()} RENAME COLUMN $old TO $new;");
                break;
                default:
                    return false;
                break;
            }
        }

        /**
         *
         * Convert a table
         *
         * @method convert
         *
         * @param  string  $charset The charset
         * @param  string  $collate The collation
         *
         * @return bool
         *
         * @throws Exception
         *
         **/
        public function convert(string $charset,string $collate): bool
        {
            is_true(collection($this->all_charset)->not_exist($charset),true,"The charset $charset is not valid");

            is_true(collection($this->all_collation)->not_exist($collate),true,"The collation $collate is not valid");

            switch ($this->driver)
            {
                case Connect::MYSQL:
                    return $this->connexion->execute("ALTER TABLE {$this->current()} CONVERT TO CHARACTER SET $charset COLLATE $collate");
                break;
                case Connect::POSTGRESQL:
                    return $this->set_charset($charset)->change_charset() && $this->set_collation($collate)->change_collation();
                break;
                default:
                    return false;
                break;
            }
        }

        /**
         *
         * Get the columns length
         *
         * @method get_columns_length
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function columns_length(): array
        {
            $types = collection();

            switch ($this->driver)
            {
                case Connect::MYSQL:

                  foreach ($this->connexion->request("SHOW FULL COLUMNS FROM $this->table") as $type)
                  {
                      $x = collection(explode('(', trim($type->Type,')')));

                      $x->has_key(1) ?  $types->push($x->get(1)) : $types->push(0);
                  }

                break;

                case Connect::POSTGRESQL:

                    foreach ($this->connexion->request("select data_type FROM information_schema.columns WHERE table_name ='$this->table';") as $type)
                    {
                        $x = collection(explode('(', trim($type->data_type,')')));
                        $x->has_key(1) ? $types->push($x->get(1)) : $types->push(0);
                    }

                break;

                case Connect::SQLITE:
                    foreach ($this->connexion->request("PRAGMA table_info($this->table)") as $type)
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
         * Remove the columns
         *
         * @param string[] $columns
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function remove_column(string ...$columns): bool
        {
            $data = collection();
            $primary = $this->primary_key();
            $table = $this->current();

            foreach($columns as $k => $column)
            {
                switch ($this->driver)
                {
                    case Connect::MYSQL:
                        equal($column,$primary) ? $data->add(false) : $data->add($this->connexion->execute("ALTER TABLE $table DROP $column"));
                    break;

                    case Connect::POSTGRESQL:
                        equal($column,$primary) ? $data->add(false) : $data->add($this->connexion->execute("ALTER TABLE $table DROP COLUMN $column RESTRICT"));
                    break;

                    case Connect::SQLITE:
                        return false;
                    break;

                }
            }
            return $data->not_exist(false);
        }


        /**
         *
         * Check if a table not exist
         *
         * @method not_exist
         *
         * @param  string $table The table name
         *
         * @return bool
         *
         * @throws Exception
         */
        public function not_exist(string $table = ""): bool
        {
            return def($table) ? collection($this->all)->not_exist($table) :  collection($this->all)->not_exist($this->table);
        }


        /**
         *
         * Check if a table exist
         *
         * @method exist
         *
         * @param  string $table The table name
         *
         * @return bool
         *
         */
        public function exist(string $table = ""): bool
        {
            return def($table) ? collection($this->all)->exist($table) :  collection($this->all)->exist($this->table);
        }

        /**
         *
         * Save the values in the current table
         *
         * @method save
         *
         * @param  array $values The values
         * @param  string $table The table name
         * @param  array $ignore The value to ignore
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function save(array $values,string $table,array $ignore = []): bool
        {
            $this->table = $table;

            $primary = $this->primary_key();

            $columns = '(' . collection($this->columns)->join(', ') .') ';

            $val = collection();

            $ignore = collection($ignore);

            foreach ($values as $key => $value)
            {
                if (different($key,$primary))
                {
                    if ($ignore->not_exist($value))
                    {
                        if ($val->not_exist($value))
                        {

                            if ($val->numeric($value))
                                $val->push($value);
                            else
                                $val->push($this->connexion->instance()->quote($value));
                        }
                    }

                }
                else
                {
                    switch ($this->driver)
                    {
                        case Connect::POSTGRESQL:
                            $val->push(" DEFAULT");
                        break;
                        default:
                            $val->push('NULL');
                        break;
                    }
                }

            }

            $value = '(' .$val->join(', ') . ')';

            $command = "INSERT INTO  $this->table  $columns VALUES $value";

            return $this->connexion->execute($command);
        }


        /**
         *
         * Display all tables
         *
         * @method show
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function show(): array
        {
            $tables = collection();

            $hidden = def($this->hidden) ?  collection($this->hidden) : collection();


            switch ($this->driver)
            {
                case Connect::MYSQL :

                    foreach ($this->connexion->request("SHOW TABLES") as $table)
                    {
                        $tab = current($table);
                        if ($hidden->empty())
                        {
                            $tables->push($tab);
                        }else{
                            if ($hidden->not_exist($tab))
                                $tables->push($tab);
                        }
                    }
                break;

                case Connect::POSTGRESQL:

                    foreach ($this->connexion->request("SELECT table_name FROM information_schema.tables WHERE  table_type = 'BASE TABLE' AND table_schema NOT IN ('pg_catalog', 'information_schema');") as $table)
                    {
                        $tab = current($table);
                        if ($hidden->empty())
                        {
                            $tables->push($tab);
                        }else {
                            if ($hidden->not_exist($tab))
                                $tables->push($tab);
                        }
                    }
                break;

                case Connect::SQLITE:

                    foreach ($this->connexion->request("SELECT tbl_name FROM sqlite_master") as $table)
                    {
                        $tab = current($table);
                        if ($hidden->empty())
                        {
                            $tables->push($tab);
                        }else {
                            if ($hidden->not_exist($tab))
                                $tables->push($tab);
                        }
                    }
                break;
            }
            return $tables->collection();
        }

        /**
         *
         * Count number of records inside a table
         *
         * @param string $table
         *
         * @return int
         *
         * @throws Exception
         *
         */
        public function count(string $table): int
        {
            foreach ($this->connexion->request("SELECT COUNT(*) FROM $table") as $number)
                return current($number);


            return 0;

        }

        /**
         *
         * Select all records in a table
         *
         * @method all
         *
         * @param  string $order The order clause
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function all(string $order = Table::DESC): array
        {
            return def($order) ? $this->connexion->request("SELECT * FROM {$this->table} ORDER BY {$this->primary_key()} $order") : $this->connexion->request("SELECT * FROM {$this->table}");
        }

        /**
         *
         * Count all tables found
         *
         * @method found
         *
         * @return int
         *
         **/
        public function found(): int
        {
            return collection($this->all)->length();
        }

        /**
         *
         * Update a record
         *
         * @method update
         *
         * @param  int $id The record id
         * @param  array $values The new values
         * @param  string $table The table name
         * @param  array $ignore The values to ignore
         *
         * @return bool
         *
         * @throws Exception
         * 
         */
        public function update(int $id,array $values,string $table,array $ignore= []): bool
        {
            $primary = $this->primary_key();
            $columns = collection();

            $ignoreValues = collection($ignore);
            foreach ($values  as $k => $value)
            {
                if (different($k,$primary))
                {
                    if ($ignoreValues->empty())
                    {
                        if ($columns->numeric($value))
                                $columns->push("$k = $value");
                        else
                            $columns->push("$k =" .quote($this->connexion,$value));

                    }else
                    {

                        if ($ignoreValues->not_exist($value))
                        {
                            if ($columns->numeric($value))
                                $columns->push("$k = $value");
                            else
                                $columns->push("$k = ".quote($this->connexion,$value));
                        }
                    }
                }

            }

            $columns =  $columns->join(', ');

            $command = "UPDATE $table SET $columns  WHERE $primary = $id";

            return $this->connexion->execute($command);
        }


        /**
         *
         * Modify a column
         *
         * @method modify_column
         *
         * @param  string $column The column name
         * @param  string $type The column type
         * @param  int $size The column size
         *
         * @return bool
         *
         * @throws Exception
         * 
         */
        public function modify_column(string $column,string $type,int $size = 0): bool
        {
            switch ($this->driver)
            {
                case Connect::MYSQL:
                    return $size ? $this->connexion->execute("ALTER TABLE {$this->table} MODIFY $column $type($size)"):  $this->connexion->execute("ALTER TABLE {$this->table} MODIFY $column $type");
                break;
                case Connect::POSTGRESQL:
                    return $size ? $this->connexion->execute("ALTER TABLE {$this->table} ALTER COLUMN $column TYPE $type($size)") : $this->connexion->execute("ALTER TABLE {$this->table} ALTER COLUMN $column TYPE $type");
                break;
                default:
                    return false;
                break;
            }
        }

        /**
         *
         * Set engine
         *
         * @method set_engine
         *
         * @param  string     $engine The engine
         *
         * @return Table
         *
         */
        public function set_engine(string $engine): Table
        {
            $this->engine = $engine;

            return $this;
        }

        /**
         *
         * Remove all tables
         *
         * @method drop_all_tables
         *
         * @return bool
         *
         * @throws Exception
         * 
         */
        public function drop_all_tables(): bool
        {
            $hidden = collection($this->hidden);


            if ($hidden->empty())
            {
                foreach ($this->all as $table)
                    is_false($this->drop($table),true,"Failed to remove the table : $table");

            }else
            {
                foreach ($this->all as $table)
                {
                    if ($hidden->not_exist($table))
                    {
                        is_false($this->drop($table),true,"Failed to remove the table : $table");
                    }
                }

            }
            return true;
        }


        /**
         *
         * Append multiples columns before or after column
         *
         * @method append_columns
         *
         * @param  string $table
         * @param  Table $instance
         * @param  array $new_columns_names
         * @param  array $new_columns_types
         * @param  array $new_column_order
         * @param  array $existing_columns_selected
         * @param  array $unique
         * @param  array $null
         *
         * @return bool          
         * @throws Exception
         */
        public function append_columns(string $table, Table $instance, array  $new_columns_names, array $new_columns_types, array $new_column_order, array $existing_columns_selected, array $unique, array $null): bool
        {
            $table_columns = $instance->from($table)->columns();

            $the_end_of_new_columns =  collection($new_columns_names)->last();

            switch ($this->driver)
            {
                case Connect::MYSQL:

                    $command = "ALTER TABLE `$table`  ";


                    for ($i=0;$i<count($new_columns_names);$i++)
                    {
                        $columnName     = $new_columns_names[$i];
                        $columnType     = $new_columns_types[$i];
                        $columnLength   = $new_columns_types[$i];
                        $columnSelected = $existing_columns_selected[$i];

                        $isFirst        = equal($new_column_order[$i],'FIRST');
                        $isUnique       = equal($unique[$i],true);
                        $isNullable     = equal($null[$i],true);
                        $islength       = def($columnLength);
                        $isTheEnd       = equal($new_columns_names[$i],$the_end_of_new_columns);



                        $columnPrev = before_key($table_columns,$columnSelected);

                        // UNIQUE WITH LENGTH

                        if ($islength)
                        {
                            if ($isUnique)
                            {
                                if ($isNullable)
                                {

                                    if ($isFirst)
                                    {
                                        if ($isTheEnd)
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType($columnLength) UNIQUE AFTER `$columnPrev` ;";
                                        }else
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType($columnLength) UNIQUE AFTER `$columnPrev` , ";
                                        }
                                    }
                                    else
                                    {
                                        if ($isTheEnd)
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType($columnLength) UNIQUE AFTER `$columnSelected` ;";
                                        }else
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType($columnLength) UNIQUE AFTER `$columnSelected` , ";
                                        }
                                    }

                                }

                                // UNIQUE  NOT NULL WITH LENGTH

                                else
                                {
                                    if ($isFirst)
                                    {
                                        if ($isTheEnd)
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType($columnLength) UNIQUE NOT NULL AFTER `$columnPrev` ;";
                                        }else
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType($columnLength) UNIQUE NOT NULL AFTER `$columnPrev` , ";
                                        }
                                    }else
                                    {

                                        if ($isTheEnd)
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType($columnLength) UNIQUE NOT NULL AFTER `$columnSelected` ;";
                                        }else
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType($columnLength) UNIQUE NOT NULL AFTER `$columnSelected` , ";
                                        }
                                    }
                                }
                            }

                            // NOT UNIQUE WITH LENGTH

                            else{

                                if ($isNullable)
                                {
                                    if ($isFirst)
                                    {
                                        if ($isTheEnd)
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType($columnLength) AFTER `$columnPrev` ;";
                                        }else
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType($columnLength) AFTER `$columnPrev` , ";
                                        }

                                    }

                                    // NOT UNIQUE NOT NULL WITH LENGTH

                                    else
                                    {

                                        if ($isTheEnd)
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType($columnLength) NOT NULL AFTER `$columnSelected` ;";

                                        }else
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType($columnLength) NOT NULL AFTER `$columnSelected` , ";
                                        }
                                    }
                                }
                            }


                        }

                        // NO LENGTH

                        else
                        {

                            if ($isUnique)
                            {
                                if ($isNullable)
                                {

                                    if ($isFirst)
                                    {
                                        if ($isTheEnd)
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType  UNIQUE AFTER `$columnPrev` ;";
                                        }else
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType UNIQUE AFTER `$columnPrev` , ";
                                        }
                                    }
                                    else
                                    {
                                        if ($isTheEnd)
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType UNIQUE AFTER `$columnSelected` ;";
                                        }else
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType UNIQUE AFTER `$columnSelected` , ";
                                        }
                                    }

                                }

                                // UNIQUE  NOT NULL WITH LENGTH

                                else
                                {
                                    if ($isFirst)
                                    {
                                        if ($isTheEnd)
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType UNIQUE NOT NULL AFTER `$columnPrev` ;";
                                        }else
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType UNIQUE NOT NULL AFTER `$columnPrev` , ";
                                        }
                                    }else
                                    {

                                        if ($isTheEnd)
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType UNIQUE NOT NULL AFTER `$columnSelected` ;";
                                        }else
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType UNIQUE NOT NULL AFTER `$columnSelected` , ";
                                        }
                                    }
                                }
                            }

                            // NOT UNIQUE WITH LENGTH

                            else{

                                if ($isNullable)
                                {
                                    if ($isFirst)
                                    {
                                        if ($isTheEnd)
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType AFTER `$columnPrev` ;";
                                        }else
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType AFTER `$columnPrev` , ";
                                        }

                                    }

                                    // NOT UNIQUE NOT NULL WITH LENGTH

                                    else
                                    {

                                        if ($isTheEnd)
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType NOT NULL AFTER `$columnSelected` ;";

                                        }else
                                        {
                                            $command .= "ADD COLUMN `$columnName` $columnType NOT NULL AFTER `$columnSelected` , ";
                                        }
                                    }
                                }
                            }

                        }


                    }
                    return $this->connexion->execute($command);
                break;
                default:
                    return false;
                break;
            }


        }

        /**
         *
         * Check if the current table has types
         *
         * @method has_types
         *
         * @param  string[] $types All types expected
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has_types(string ...$types): bool
        {
            $x = collection($this->types);

            foreach ($types as $type)
            {
                if ($x->not_exist($type))
                    return false;
            }
            return true;
        }

        /**
         *
         * Rename a table
         *
         * @method rename
         *
         * @param  string $new_name The new name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function rename(string $new_name = ''): bool
        {
            $new_name = def($new_name) ? $new_name : $this->get_current_tmp_table();

            switch ($this->driver)
            {
                case Connect::MYSQL :
                    $data =  $this->connexion->execute("RENAME TABLE {$this->table} TO $new_name");
                    assign($data,$this->table,$new_name);
                    return $data;
                break;
                case Connect::POSTGRESQL:
                case Connect::SQLITE:
                    $data =   $this->connexion->execute("ALTER TABLE {$this->table} RENAME TO $new_name");
                    assign($data,$this->table,$new_name);
                    return $data;
                break;
                default:
                    return false;
                break;
            }
        }

        /**
         *
         * Display columns types
         *
         * @method columns_types
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function columns_types(): array
        {
            $types = collection();

            switch ($this->driver)
            {
                case Connect::MYSQL:

                  foreach ($this->connexion->request("SHOW FULL COLUMNS FROM $this->table") as $type)
                  {
                      $x = collection(explode('(', trim($type->Type,')')));
                      $types->push($x->get(0));
                  }

                break;

                case Connect::POSTGRESQL:

                    foreach ($this->connexion->request("select data_type FROM information_schema.columns WHERE table_name ='$this->table';") as $type)
                    {
                        $x = collection(explode('(', trim($type->data_type,')')));
                        $types->push($x->get(0));
                    }

                break;

                case Connect::SQLITE:
                    foreach ($this->connexion->request("PRAGMA table_info($this->table)") as $type)
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
         * Set the new collation
         *
         * @method set_collation
         *
         * @param  string        $new_collation The collation
         *
         * @return Table
         *
         * @throws Exception
         *
         */
        public function set_collation(string $new_collation): Table
        {

            if (!$this->connexion->sqlite())
            {
                $data = collation($this->connexion);

                is_true(collection($data)->not_exist($new_collation),true,"The collation $new_collation  is not valid");

                $this->collation = $new_collation;
            }



            return $this;
        }

        /**
         *
         * Set new collation
         *
         * @param string $new_charset
         *
         * @return Table
         *
         * @throws Exception
         *
         */
        public function set_charset(string $new_charset): Table
        {
            if ($this->connexion->mysql())
            {
                $data = charset($this->connexion);

                is_true(collection($data)->not_exist($new_charset),true,"The charset $new_charset is not valid");

                $this->charset = $new_charset;
            }

            if ($this->connexion->postgresql())
            {
                $data = charset($this->connexion);

                is_true(collection($data)->not_exist(strtoupper($new_charset)),true,"The charset $new_charset is not valid");

                $this->charset = $new_charset;
            }

            return $this;
        }

        /**
         *
         * Return the column length
         *
         * @method length
         *
         * @param  string $column The column name
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function length(string $column)
        {
            return collection($this->columns())->search($column)->set_new_data($this->columns_length())->result();
        }

        /**
         *
         * Return the current table name
         *
         * @method get_current_tmp_table
         *
         * @return string
         *
         */
        public function get_current_tmp_table(): string
        {
            return '_'.sha1($this->current());
        }

        /**
         *
         * Insert multiples data in the table
         *
         * @method insert_multiples
         *
         * @param  array $collection The data to insert
         * @param  array $ignore The data to ignore
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function insert_multiples(array $collection,array $ignore = []): bool
        {

            $query = "INSERT INTO {$this->current()} ({$this->columns_to_string()}) VALUES ";

            $primary = $this->primary_key();

            $hidden = collection($ignore);
            $values = collection();

            foreach ($collection as  $items)
            {
                foreach ($items as $key => $value)
                {

                    if (different($key,$primary))
                    {
                        if ($hidden->not_exist($value))
                        {
                            if ($values->not_exist($value))
                            {

                                if ($values->numeric($value))
                                    $values->push($value);
                                else
                                    $values->push($this->connexion->instance()->quote($value));
                            }
                        }
                    }
                    else
                    {

                        switch ($this->driver)
                        {
                            case Connect::POSTGRESQL:
                                $values->push(" DEFAULT");
                            break;
                            default:
                                $values->push('NULL');
                            break;
                        }
                    }

                }


                $value = '(' .$values->join(', ') . '),';

                append($query,$value);

                $values->clear();
            }

            $query = trim($query,',');


           return $this->connexion->execute($query);

        }

    }

}
