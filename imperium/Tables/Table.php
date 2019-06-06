<?php


namespace Imperium\Tables {

    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\Connexion\Connect;
    use Imperium\Import\Import;
    use Imperium\Zen;
    use Imperium\App;

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
         * @var Column
         */
        private $column;


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
         *
         * @throws Exception
         *
         */
        public function __construct(Connect $connect)
        {
            $this->connexion        = $connect;
            $this->driver           = $connect->driver();
            $this->added_columns    = collection();
            $this->all_types        = collection()->merge(self::DATE_TYPES,self::NUMERIC_TYPES,self::TEXT_TYPES)->collection();
            $this->all_collation    = collation($connect);
            $this->all_charset      = charset($connect);
            $this->column = new Column($connect);
        }


        /**
         *
         * Get an instance of column
         *
         * @return Column
         *
         */
        public function column(): Column
        {
            return $this->column;
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
            return $this->table;
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
                case MYSQL :
                    return $this->connexion->execute("ALTER TABLE {$this->current()} COLLATE ?;",$this->collation);
                break;
                case POSTGRESQL :
                    return $this->connexion->execute("update pg_database set datcollate='?', datctype='?' where datname = '{$this->connexion->base()}'",$this->collation,$this->collation);
                break;
                default :
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
                case MYSQL :
                    return $this->connexion->execute("ALTER TABLE {$this->current()} CHARACTER SET = ?;",$this->charset);
                break;
                case POSTGRESQL :
                    return $this->connexion->execute("update pg_database set encoding = pg_char_to_encoding('?') where datname = '{$this->connexion->base()}'",$this->charset);
                break;
                default :
                    return false;
                break;
            }
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
                case MYSQL :
                    return $this->connexion->execute("TRUNCATE TABLE $table");
                break;
                case POSTGRESQL :
                    return $this->connexion->execute("TRUNCATE TABLE $table RESTART IDENTITY");
                break;
                case SQLITE :
                    return $this->connexion->execute("DELETE  FROM $table") && $this->connexion->execute('VACUUM');
                break;
                default:
                    return false;
                break;
            }


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
                case MYSQL;
                    return  $this->connexion->execute("ALTER TABLE {$this->current()} DROP CONSTRAINT ?;",$constraint);
                break;
                case SQLITE:
                    return $this->connexion->execute("ALTER TABLE {$this->current()} ALTER COLUMN ? DROP ?;",$column,$constraint);
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
                $end =  has($column[App::FIELD_NAME],$columns->last());

                if($column[Table::FIELD_UNIQUE])
                    $uniq->add($column[Table::FIELD_NAME]);

                append($command,$this->updateCreateCommand($column,$end));

            }


            append($command, ' )');

            $this->added_columns = collection();

            $data = $this->connexion->execute($command);

            is_false($data,true,$command);

           return $data;
        }


        /**
         *
         * Execute the sql file content
         *
         * @method import
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function import(): bool
        {
            return (new Import())->import();
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

            $size = $x->get(App::FIELD_LENGTH);

            $command = '';

            append($command,"{$x->get(App::FIELD_NAME)} ");

            $size ? append($command," {$x->get(App::FIELD_TYPE)}($size)") : append($command," {$x->get(App::FIELD_TYPE)} ");



            if ($x->get(App::FIELD_PRIMARY))
            {
                switch ($this->driver)
                {
                    case MYSQL:
                         append($command,' AUTO_INCREMENT PRIMARY KEY');
                    break;
                    case POSTGRESQL:
                          append($command,'  PRIMARY KEY');
                    break;
                    case SQLITE:
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
            $rec = $this->column->for($this->current());

            $columns = $rec->show();
            $columns_str = collection($columns)->join(',');

            $query = "INSERT INTO {$this->current()} ($columns_str) VALUES ";

            $primary = $rec->primary_key();

            $x = collection();

            $sqlite = $this->connexion->sqlite();

            $types = collection($rec->types());

            for($i=0;different($i,$records);$i++)
            {
                foreach ($columns as $k => $column)
                {
                    $type = $sqlite ? strtolower($types->get($k)) : $types->get($k);

                    if (equal($column,$primary))
                    {
                        switch ($this->driver)
                        {
                            case MYSQL:
                            case SQLITE:
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
                            $x->add(true_or_false($this->driver),$column);


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

                        if (has($type,self::TEXT_TYPES))
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
                case MYSQL:
                    return self::MYSQL_TYPES;
                break;
                case POSTGRESQL:
                    return self::POSTGRESQL_TYPES;
                break;
                case SQLITE:
                    return self::SQLITE_TYPES;
                break;
                default:
                    return [];
                break;
            }
        }


        /**
         *
         * Dump a table
         *
         * @method dump
         *
         * @param string[] $tables
         * @return bool
         *
         * @throws Exception
         */
        public function dump(string ...$tables): bool
        {
            return def($tables) ? dumper(false,$tables) : dumper(false,[$this->current()]);
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
            return $this->connexion->request("SELECT * FROM {$this->current()} WHERE {$this->column->for($this->current())->primary_key()} = ?",$id );
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
            $data = $this->from($this->current())->select($id);

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
            return $this->connexion->execute("DELETE FROM {$this->current()} WHERE {$this->column->for($this->current())->primary_key()} = ?",$id);
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
                case MYSQL:
                    return $this->connexion->execute("ALTER TABLE {$this->current()} CONVERT TO CHARACTER SET ? COLLATE ? ",$charset,$collate);
                break;
                case POSTGRESQL:
                    return $this->set_charset($charset)->change_charset() && $this->set_collation($collate)->change_collation();
                break;
                default:
                    return false;
                break;
            }
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
         *
         */
        public function not_exist(string $table): bool
        {
            return  collection($this->show())->not_exist($table);
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
         * @throws Exception
         *
         */
        public function exist(string $table): bool
        {
            return collection($this->show())->exist($table);
        }

        /**
         *
         * Save the values in the current table
         *
         * @method save
         *
         * @param  array $values The values
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function save(array $values): bool
        {
            return $this->connexion->execute(insert_into($this->current(),$values));
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

            $hidden = collection($this->hidden_tables());

            switch ($this->driver)
            {
                case MYSQL :

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

                case POSTGRESQL :

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

                case SQLITE :

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
         */
        public function count(string $table =  ''): int
        {
            $table = def($table) ? $table : $this->current();

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
         * @param string $column
         * @param string $order The order clause
         *
         * @return array
         *
         * @throws Exception
         */
        public function all(string $column ='',string $order = DESC): array
        {
            $column = def($column) ? $column : $this->column->for($this->current())->primary_key();

            return $this->connexion->request("SELECT * FROM {$this->current()} ORDER BY $column $order");
        }

        /**
         *
         * Count all tables found
         *
         * @method found
         *
         * @return int
         *
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
         * Update a record
         *
         * @method update
         *
         * @param  int $id The record id
         * @param  array $values The new values
         * @param  array $ignore The values to ignore
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function update(int $id,array $values,array $ignore= []): bool
        {
            $primary = $this->column->for($this->current())->primary_key();

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
                            $columns->push("$k = {$this->connexion->instance()->quote($value)}");
                    }else
                    {

                        if ($ignoreValues->not_exist($value))
                        {
                            if ($columns->numeric($value))
                                $columns->push("$k = $value");
                            else
                                $columns->push("$k = {$this->connexion->instance()->quote($value)}");
                        }
                    }
                }

            }

            $columns =  $columns->join(', ');

            $sql = "UPDATE  {$this->current()} SET $columns WHERE $primary = $id";


            return $this->connexion->execute($sql);
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
        public function rename(string $new_name): bool
        {

            is_false(def($this->table),true,"Missing the table name");

            switch ($this->driver)
            {
                case MYSQL :
                    $data =  $this->connexion->execute("RENAME TABLE {$this->current()} TO ?",$new_name);
                    assign($data,$this->table,$new_name);
                    return $data;
                break;
                case POSTGRESQL :
                case SQLITE :
                    $data =   $this->connexion->execute("ALTER TABLE {$this->current()} RENAME TO ?",$new_name);
                    assign($data,$this->table,$new_name);
                    return $data;
                break;
                default :
                    return false;
                break;
            }
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

                is_true(collection($this->all_charset)->not_exist($new_charset),true,"The charset $new_charset is not valid");

                $this->charset = $new_charset;
            }

            if ($this->connexion->postgresql())
            {

                is_true(collection($this->all_charset)->not_exist(strtoupper($new_charset)),true,"The charset $new_charset is not valid");

                $this->charset = $new_charset;
            }

            return $this;
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

            $rec = $this->column->for($this->current());
            $query = "INSERT INTO {$this->current()} ({$rec->columns_to_string()}) VALUES ";

            $primary = $rec->primary_key();

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
                            case POSTGRESQL:
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

        /**
         *
         * Show hidden tables
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function hidden_tables(): array
        {
            return config('db','hidden_tables');
        }

    }

}
