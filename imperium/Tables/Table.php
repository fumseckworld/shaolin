<?php


namespace Imperium\Tables {

    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\Connexion\Connect;
    use Imperium\File\File;

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
         *  [private description]
         *
         *  @var [type]
         */
        private $check;

        /**
         *
         *  ALl date type
         *
         * @var array
         *
         */
        const TYPE_OF_DATE = [
            'date',
            'datetime',
            'interval',
            'time',
            'timestamp',
            'year',
            'interval',
            'timestamp with time zone',
            'time without time zone',
            'time with time zone',
            'timestamp without time zone'
        ];

        /**
         *
         * All number type
         *
         * @var array
         *
         */
        const TYPE_OF_INTEGER = [
            'int',
            'decimal',
            'double precision',
            'bigint',
            'real',
            'double',
            'numeric',
            'bigserial',
            'bit',
            'serial',
            'smallserial',
            'numeric',
            'bigint',
            'bigserial',
            'int2',
            'int8',
            'float',
            'integer',
            'tinyint',
            'smallint',
            'mediumint'
        ];

        /**
         *
         * All text type
         *
         * @var array
         *
         */
        const TYPE_OF_TEXT = [
            'varchar',
            'char',
            'binary',
            'varbinary',
            'character varying',
            'character',
            'blob',
            'enum',
            'text',
            'mediumtext',
            'tinytext',
            'longtext'
        ];

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
            return collection($this->get_columns())->search($column)->set_new_data($this->get_columns_types())->result();
        }

        /**
         *
         * @method check
         *
         * @param  string $column    [description]
         * @param  string $condition [description]
         * @param  mixed  $expected  [description]
         *
         * @return string
         *
         */
        private function check(string $column,string $condition,$expected): string
        {
            return is_string($expected) ?  "CHECK ($column $condition '$expected')" :  "CHECK ($column $condition $expected)";
        }

        /**
         *
         * Define name of the current  table
         *
         * @param string $table
         *
         * @return Table
         *
         */
        public function select(string $table): Table
        {
            $this->table = $table;

            return $this;
        }
        /**
         * Table constructor.
         *
         * @param Connect $connect
         */
        public function __construct(Connect $connect)
        {
             $this->connexion =  $connect;
             $this->driver  =  $connect->driver();
             $this->added_columns = collection();
        }


        /**
         *
         * Return the current table name
         *
         * @return string
         *
         * @throws Exception
         */
        public function get_current_table(): string
        {
            return  $this->table;
        }

        /**
         *
         *
         * @method get_columns_info
         *
         * @return array
         *
         */
        public function get_columns_info(): array
        {
            $fields = collection();

            switch ($this->driver)
            {
                case Connect::MYSQL :

                    foreach ($this->connexion->request("SHOW FULL COLUMNS FROM {$this->get_current_table()}") as $column)
                        $fields->push($column);
                break;

                case Connect::POSTGRESQL :

                    foreach ($this->connexion->request("SELECT * FROM information_schema.columns WHERE table_name ='{$this->get_current_table()}'") as $column)
                        $fields->push($column);

                break;

                case Connect::SQLITE :
                    foreach ($this->connexion->request("PRAGMA table_info({$this->get_current_table()})") as $column)
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
            switch ($this->driver)
            {
                case Connect::MYSQL;
                    return $this->connexion->execute("ALTER TABLE {$this->get_current_table()} COLLATE {$this->collation};");
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
            switch ($this->driver)
            {
                case Connect::MYSQL;
                    return $this->connexion->execute("ALTER TABLE {$this->get_current_table()} CHARACTER SET = {$this->charset};");
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
         * Check if a column exist
         *
         * @param string $column
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has_column(string $column): bool
        {
            return collection($this->get_columns())->exist($column);
        }


        /**
         *
         * Check if a column exist
         *
         * @param string $column
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function column_not_exist(string $column): bool
        {
            return collection($this->get_columns())->not_exist($column);
        }

        /**
         *
         * Return all columns inside a table
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function get_columns(): array
        {
            $fields = collection();

            switch ($this->driver)
            {
                case Connect::MYSQL :

                    foreach ($this->connexion->request("SHOW FULL COLUMNS FROM {$this->get_current_table()}") as $column)
                        $fields->push($column->Field);

                break;

                case Connect::POSTGRESQL :

                    foreach ($this->connexion->request("SELECT column_name FROM information_schema.columns WHERE table_name ='{$this->get_current_table()}'") as $column)
                         $fields->push($column->column_name);

                break;

                case Connect::SQLITE :
                    foreach ($this->connexion->request("PRAGMA table_info({$this->get_current_table()})") as $column)
                        $fields->push($column->name);
                break;
            }

            return $fields->collection();
        }

        /**
         *
         * Remove a table
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function drop(string $table = '' ): bool
        {
            return def($table) ? $this->connexion->execute("DROP TABLE $table") :  $this->connexion->execute("DROP TABLE {$this->get_current_table()}");
        }

        /**
         *
         * Empty a table
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function truncate(string $table = ''): bool
        {
            if (def($table))
                $this->table = $table;


            switch ($this->driver)
            {
                case Connect::MYSQL :
                    return $this->connexion->execute("TRUNCATE TABLE {$this->get_current_table()}");
                break;
                case Connect::POSTGRESQL :
                    return $this->connexion->execute("TRUNCATE TABLE {$this->get_current_table()}  RESTART IDENTITY");
                break;

                case Connect::SQLITE :
                    return $this->truncateSqliteTable($this->get_current_table());
                break;
                default:
                    return false;
                break;
            }


        }

        /**
         *
         * Append a field in create task
         *
         * @method field
         *
         * @param  string $type     [description]
         * @param  string $name     [description]
         * @param  bool   $primary  [description]
         * @param  int    $length   [description]
         * @param  bool   $unique   [description]
         * @param  bool   $nullable [description]
         *
         * @return Table
         *
         */
        public function field(string $type, string $name, bool $primary, int $length, bool $unique, bool $nullable,bool $check,string $check_condition,$check_expected): Table
        {

            $x = collection()
                ->add($name,self::FIELD_NAME)
                ->add($type,self::FIELD_TYPE)
                ->add($primary, self::FIELD_PRIMARY)
                ->add($length, self::FIELD_LENGTH )
                ->add($unique,self::FIELD_UNIQUE)
                ->add($nullable,self::FIELD_NULLABLE)
                ->add($check,self::CHECK)
                ->add($check_condition,self::CHECK_CONDITION)
                ->add($check_expected,self::CHECK_EXPECTED)
            ->collection();

            $this->added_columns->push($x);

            return $this;
        }

        /**
         *
         * Append column inside an existing table
         *
         * @param string $name
         * @param string $type
         * @param int    $size
         * @param bool   $unique
         * @param bool   $nullable
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function append_column(string $name, string $type, int $size, bool $unique,bool $nullable): bool
        {

            $data = collection();
            $command = "ALTER TABLE {$this->get_current_table()} ADD COLUMN ";

            different($size,0) ?  append($command,"$name $type($size) ") :  append($command,"$name $type ");



            if(is_false($nullable))
                append($command,' NOT NULL');

            $data->add($this->connexion->execute($command));

            if($unique)
                $data->add($this->alter_table(Imperium::FIELD_UNIQUE,$name));

            return $data->not_exist(false);
        }



        /**
         *
         * @param string $constraint
         * @param string $column
         *
         * @return bool
         *
         * @throws Exception
         */
        public function alter_table(string $constraint,string $column): bool
        {

            if (equal($this->driver,Connect::MYSQL))
            {
                if (equal($constraint,Imperium::FIELD_UNIQUE))
                {
                    return $this->connexion->execute("ALTER TABLE {$this->get_current_table()} ADD  UNIQUE ($column)");
                }
            }

            if (equal($this->driver,Connect::POSTGRESQL))
            {
                if (equal($constraint,Imperium::FIELD_UNIQUE))
                {
                    return $this->connexion->execute("ALTER TABLE {$this->get_current_table()} ADD UNIQUE ($column);");
                }
            }

            if (equal($this->driver,Connect::SQLITE))
            {
                if (equal($constraint,Imperium::FIELD_UNIQUE))
                {
                    return $this->connexion->execute("CREATE  UNIQUE INDEX IF NOT EXISTS uniq_$column ON {$this->get_current_table()}($column);");
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
                    return  $this->connexion->execute("ALTER TABLE {$this->get_current_table()} DROP CONSTRAINT $constraint;");
                break;
                case Connect::SQLITE:
                    return $this->connexion->execute("ALTER TABLE {$this->get_current_table()} ALTER COLUMN $column DROP $constraint;");
                break;
                default:
                    return false;
                break;
            }

        }

        /**
         *
         * Create the new table
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function create(): bool
        {

            $command = $this->startCreateCommand();

            $columns = $this->added_columns;



            foreach ($columns->collection() as $column)
            {
                $end =  has($column[Imperium::FIELD_NAME],$columns->last());
                append($command,$this->updateCreateCommand($column,$end));

            }


            append($command, ' )');

            $this->added_columns = collection();


            return  $this->connexion->execute($command);
        }

        /**
         *
         * Execute the sql file content
         *
         * @method import
         *
         * @param  string $sql_file [description]
         *
         * @return bool
         *
         * @throws Exception
         *
         **/
        public function import(string $sql_file): bool
        {
            $sql = $this->connexion->dump_path() . '/'. $sql_file;
            switch ($this->driver)
            {
                case Connect::POSTGRESQL:
                break;
                case Connect::SQLITE:
                    return system("sqlite3 {$this->connexion->base()} < $sql") && def($this->all());
                break;
                default:
                    return $this->connexion->execute(File::content($sql));
                break;
            }
            return $this->connexion->execute(File::content($sql));
        }
        /**
         * start creation of a table
         *
         * @return string
         *
         * @throws Exception
         *
         */
        private function startCreateCommand(string $table = '') : string
        {
            $x = def($table) ? $table : $this->get_current_table();
            $code = "CREATE TABLE IF NOT EXISTS $x ";
            append($code,'(  ');

            return $code;
        }

        /**
         *
         * Verify is the current field is the last
         *
         * @param       $field
         *
         * @param array $columns
         *
         * @return bool
         *
         * @throws Exception
         */
        public function is_the_last_field($field,array $columns): bool
        {
            return equal($field,collection($columns)->last());
        }

        /**
         * append field in command to create table
         *
         *
         * @param array $field
         *
         * @param bool  $end
         *
         * @return string
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
                    if ($x->get(Imperium::FIELD_UNIQUE)){ append($command,' UNIQUE'); }

                    if ($x->get(Imperium::FIELD_NULLABLE)){ append($command,'  NOT NULL'); }

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

            $query = "INSERT INTO {$this->get_current_table()} ({$this->columns_to_string()}) VALUES ";

            $primary = $this->get_primary_key();

            $x = collection();

            $columns = $this->get_columns();

            $types = collection();

            foreach ($this->get_columns_types() as  $value)
            {
                $data = explode('(', trim($value,')'));
                $types->push(strtolower(reset($data)));
            }


            for($i=0;different($i,$records);$i++)
            {
                foreach ($columns as $k => $column)
                {
                    $type = $types->get($k);

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

                        if (has($type,self::TYPE_OF_DATE))
                            $x->add($this->connexion->instance()->quote(faker()->date()),$column);

                        if (has($type,self::TYPE_OF_INTEGER))
                            $x->add(faker()->numberBetween(1,100),$column);

                        if (has($type,self::TYPE_OF_TEXT))
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
         * set hidden tables
         *
         * @param array $hidden
         *
         * @return Table
         */
        public function hidden(array $hidden) : Table
        {
            $this->hidden = $hidden;

            return $this;
        }

        /**
         * dump a table
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         */
        public function dump(string $table = ''): bool
        {
            return def($table) ? dumper($this->connexion,false,$table) : dumper($this->connexion,false,$this->get_current_table());
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

                    foreach ($this->connexion->request("show columns from {$this->get_current_table()} where `Key` = 'PRI';") as $key)
                        return $key->Field;

                break;

                case Connect::POSTGRESQL:

                    foreach($this->connexion->request ("select column_name FROM information_schema.key_column_usage WHERE table_name = '{$this->get_current_table()}';") as $key)
                        return $key->column_name;
                break;

                case Connect::SQLITE:

                    foreach ($this->connexion->request("PRAGMA table_info({$this->get_current_table()})") as $field)
                    {
                        if (def($field->pk))
                            return $field->name;
                    }

                break;
            }
            return '';
        }

        /**
         * get the primary key
         *
         * @return string
         *
         * @throws Exception
         */
        public function get_primary_key(): string
        {
            $primary = $this->detectPrimaryKey();


            if (not_def($primary))
                throw new Exception('We have not found a primary key');

            return $primary;

        }


        /**
         * verify if the record is empty
         *
         * @return bool
         *
         * @throws Exception
         */
        public function is_empty(): bool
        {
            return empty($this->all());
        }

        /**
         * select a record by id
         *
         * @param int $id
         *
         * @return array
         *
         * @throws Exception
         */
        public function select_by_id(int $id): array
        {
            return $this->connexion->request("SELECT * FROM {$this->get_current_table()} WHERE {$this->get_primary_key()} = $id" );
        }


        /**
         *
         * select a record by id or fail
         *
         * @param int $id
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function select_by_id_or_fail(int $id): array
        {
            $data = $this->connexion->request("SELECT * FROM {$this->get_current_table()} WHERE {$this->get_primary_key()} = $id" );

            superior($data,1,true,"The primary key is not unique");

            inferior($data,1,true,"The records was not found");

            return $data;
        }

        /**
         * delete a record by id
         *
         * @param int $id
         *
         * @return bool
         *
         * @throws Exception
         */
        public function remove_by_id(int $id): bool
        {
            return $this->connexion->execute("DELETE FROM {$this->get_current_table()} WHERE {$this->get_primary_key()} = $id");
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
            return collection($this->get_columns())->join($glue);
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
            return collection($this->get_columns())->change_value($old,$new)->join($glue);
        }

        /**
         * rename a existing column
         *
         * @param string $old
         * @param string $new
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

                    $length = $length ?  "($length)" : '';

                    return equal($old,$this->get_primary_key()) ? false : $this->connexion->execute("ALTER TABLE {$this->get_current_table()} CHANGE COLUMN  $old $new $type$length ;");
                break;
                case Connect::POSTGRESQL:
                case Connect::SQLITE:
                    return equal($old,$this->get_primary_key()) ? false : $this->connexion->execute( "ALTER TABLE {$this->get_current_table()} RENAME COLUMN $old TO $new;");
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
         * @param string $charset
         * @param string $collate
         *
         * @return bool
         *
         * @throws Exception
         */
        public function convert(string $charset,string $collate): bool
        {
            switch ($this->driver)
            {
                case Connect::MYSQL:
                    return $this->connexion->execute("ALTER TABLE {$this->get_current_table()} CONVERT TO CHARACTER SET $charset COLLATE $collate");
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
         *  Get the columns length
         *
         *  @method get_columns_length
         *
         *  @return array
         *
         */
        public function get_columns_length(): array
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
            $primary = $this->get_primary_key();
            $table = $this->get_current_table();
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
         * check if a table exist
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         */
        public function exist(string $table = ""): bool
        {
            return def($table) ? collection($this->show())->exist($table) :  collection($this->show())->exist($this->table);
        }

        /**
         * insert values
         *
         * @param array $values
         * @param array $toIgnore
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         */
        public function insert($values,array $toIgnore = [],string $table = ''): bool
        {
            if (def($table))
                $this->table = $table;


            $primary = $this->get_primary_key();

            $columns = '(' . collection($this->get_columns())->join(', ') .') ';

            $val = collection();
            $ignore = collection($toIgnore);

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
         * show all tables in a database
         *
         * @return array
         *
         * @throws Exception
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
        public function count(string $table = ''): int
        {
            $x = def($table) ? $table : $this->table;

            foreach ($this->connexion->request("SELECT COUNT(*) FROM {$x}") as $number)
                return current($number);


            return 0;

        }



        /**
         * truncate a sqlite table
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Exception
         */
        private function truncateSqliteTable(string $table) : bool
        {

            $fields = collection($this->get_columns());

            $types = collection($this->get_columns_types());

            $primary = $this->get_primary_key();

            $end = $fields->last();

            $this->drop($table);

            $query = "CREATE TABLE $table ";

            append($query,' ( ');

            foreach ($fields as $k => $field)
            {
                switch ($field)
                {
                    case $primary:
                        equal($field,$end) ? append($query ," $primary {$types->get($k)} PRIMARY KEY AUTOINCREMENT") :  append($query ," $primary {$types->get($k)} PRIMARY KEY AUTOINCREMENT ,");
                    break;
                    default:
                        equal($field,$end) ? append( $query," '$field' {$types->get($k)}") : append($query ," '$field' {$types->get($k)} ,");
                    break;
                }

            }
            append($query,')');
            return $this->connexion->execute($query);
        }

        /**
         * get all record in a table
         *
         * @param string $orderBy
         *
         * @return array
         *
         * @throws Exception
         */
        public function all(string $orderBy = 'desc'): array
        {
            return def($orderBy) ? $this->connexion->request("SELECT * FROM {$this->table} ORDER BY {$this->get_primary_key()} $orderBy") : $this->connexion->request("SELECT * FROM {$this->table}");
        }

        /**
         *
         * count all table in current database
         *
         * @return int
         *
         * @throws Exception
         */
        public function found(): int
        {
            return count($this->show());
        }

        /**
         * update a table
         *
         * @param int $id
         * @param array $values
         * @param array $ignore
         * @param string|null $table
         *
         * @return bool
         *
         * @throws Exception
         */
        public function update(int $id,array $values,array $ignore= [],string $table = ''): bool
        {
            if (def($table))
                $this->table = $table;

            $primary = $this->get_primary_key();
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
                            $columns->push("$k = ' ".addslashes($value)."'");

                    }else
                    {

                        if ($ignoreValues->not_exist($value))
                        {
                            if ($columns->numeric($value))
                                $columns->push("$k = $value");
                            else
                                $columns->push("$k = ' ".addslashes($value)."'");
                        }
                    }
                }

            }

            $columns =  $columns->join(', ');

            $command = "UPDATE {$this->table} SET $columns  WHERE $primary = $id";

            return $this->connexion->execute($command);
        }


        /**
         * modify an existing column
         *
         * @param string $column
         * @param string $type
         * @param int $size
         *
         * @return bool
         *
         * @throws Exception
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
         * set the engine
         *
         * @param string $engine
         *
         * @return Table
         */
        public function set_engine(string $engine): Table
        {
            $this->engine = $engine;

            return $this;
        }

        /**
         * delete all tables not ignored in database
         *
         * @return bool
         *
         * @throws Exception
         */
        public function drop_all_tables(): bool
        {
            $hidden = collection($this->hidden);


            if ($hidden->empty())
            {
                foreach ($this->show() as $table)
                    is_false($this->drop($table),true,"Failed to remove the table : $table");

            }else
            {
                foreach ($this->show() as $table)
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
         * appends multiple columns in a table
         *
         * @param string $table
         * @param Table $instance
         * @param array $new_columns_names
         * @param array $new_columns_types
         * @param array $new_column_order
         * @param array $existing_columns_selected
         * @param array $unique
         * @param array $null
         *
         * @return bool
         *
         * @throws Exception
         */
        public function append_columns(string $table, Table $instance, array  $new_columns_names, array $new_columns_types, array $new_column_order, array $existing_columns_selected, array $unique, array $null): bool
        {
            $table_columns = $instance->select($table)->get_columns();

            $the_end_of_new_columns =  end($new_columns_names);

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



                        $columnPrev = array_prev($table_columns,$columnSelected);

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
         * Check if the current table has specific columns types
         *
         * @param string ...$types
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has_column_type(string ...$types): bool
        {
            $x = collection($this->get_columns_types());

            foreach ($types as $type)
            {
                if ($x->not_exist($type))
                    return false;
            }
            return true;
        }

        /**
         * @param string $new_name
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
                    if ($data)
                        $this->table = $new_name;
                    return $data;
                break;
                case Connect::POSTGRESQL:
                case Connect::SQLITE:
                    $data =   $this->connexion->execute("ALTER TABLE {$this->table} RENAME TO $new_name");
                    if ($data)
                        $this->table = $new_name;
                    return $data;
                break;
                default:
                    return false;
                break;
            }
        }

        /**
         * Display columns type
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function get_columns_types(): array
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
         * Set new collation
         *
         * @param string $new_collation
         *
         * @return Table
         */
        public function set_collation(string $new_collation): Table
        {
            $this->collation = $new_collation;

            return $this;
        }

        /**
         *
         * Set new collation
         *
         * @param string $new_charset
         *
         * @return Table
         */
        public function set_charset(string $new_charset): Table
        {
            $this->charset = $new_charset;

            return $this;
        }

        /**
         *
         * @param string $column
         *
         * @return mixed
         *
         * @throws Exception
         *
         */
        public function length(string $column)
        {
            return collection($this->get_columns())->search($column)->set_new_data($this->get_columns_length())->result();
        }

        /**
         * @return string
         *
         * @throws Exception
         */
        public function get_current_tmp_table(): string
        {
            return '_'.sha1($this->get_current_table());
        }


        /**
         * @param array $collection
         * @param array $ignore
         * @return bool
         * @throws Exception
         */
        public function insert_multiples(array $collection,array $ignore = []): bool
        {

            $query = "INSERT INTO {$this->get_current_table()} ({$this->columns_to_string()}) VALUES ";

            $primary = $this->get_primary_key();

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
