<?php
/**
 * fumseck added Builder.php to imperium
 * The 09/09/17 at 19:39
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General public License for more details.
 *
 * You should have received a copy of the GNU General public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package : imperium
 * @author  : fumseck
 */


namespace Imperium\Tables {

    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\Connexion\Connect;
    use Imperium\Imperium;


    /**
     *
     * Management of table
     *
     * Class Table
     *
     * @package Imperium\Databases\Eloquent\Tables
     */
    class Table  
    {
        /**
         * @var Collection
         */
        private $columns;

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
        private $path;

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
         * Define name of the current  table
         *
         * @param string $current_table_name
         *
         * @return Table
         *
         */
        public function set_current_table(string $current_table_name): Table
        {
            $this->table = $current_table_name;

            return $this;
        }


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
            return collection($this->get_columns())->search($column)->set_new_data($this->get_columns_types())->search_result();
        }

        /**
         *
         * @param Connect $connect
         *
         * @throws Exception
         *
         */
        public function __construct(Connect $connect)
        {
             $this->connexion =  $connect;
             $this->driver  =  $connect->get_driver();
             $this->columns = collection();
             $this->added_columns = collection();
        }


        /**
         *
         * Return the current table name
         *
         * @return string
         *
         */
        public function get_current_table(): string
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
            switch ($this->driver)
            {
                case Connect::MYSQL;
                break;
                case Connect::POSTGRESQL:
                break;
                case Connect::SQLITE:
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
                break;
                case Connect::POSTGRESQL:
                break;
                case Connect::SQLITE:
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
         * Return all columns types inside a table
         *
         * @return array
         * 
         * @throws Exception
         *
         */

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

                    foreach ($this->connexion->request("SHOW FULL COLUMNS FROM $this->table") as $column)
                        $fields->push($column->Field);

                break;

                case Connect::POSTGRESQL :

                    foreach ($this->connexion->request("SELECT column_name FROM information_schema.columns WHERE table_name ='$this->table'") as $column)
                         $fields->push($column->column_name);

                break;

                case Connect::SQLITE :
                    foreach ($this->connexion->request("PRAGMA table_info($this->table)") as $column)
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
            return def($table) ? $this->connexion->execute("DROP TABLE $table") :  $this->connexion->execute("DROP TABLE {$this->table}");
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
                    return $this->connexion->execute("TRUNCATE TABLE {$this->table}");
                break;
                case Connect::POSTGRESQL :
                    return $this->connexion->execute("TRUNCATE TABLE {$this->table}  RESTART IDENTITY");
                break;

                case Connect::SQLITE :
                    return $this->truncateSqliteTable($this->table);
                break;
                default:
                    return false;
                break;
            }
     
             
        }

        /**
         *
         * Append field on create table moment
         *
         * @param string $type
         * @param string $name
         * @param bool   $primary
         * @param int    $length
         * @param bool   $unique
         * @param bool   $nullable
         *
         * @return Table
         *
         */
        public function append_field(string $type, string $name, bool $primary = false, int $length = 0, bool $unique = true, bool $nullable = false): Table
        {
            $new =  $this->columns->add($name,Imperium::FIELD_NAME)->add($type,Imperium::FIELD_TYPE)->add($primary, Imperium::FIELD_PRIMARY)->add($length, Imperium::FIELD_LENGTH )->add($unique,Imperium::FIELD_UNIQUE)->add($nullable,Imperium::FIELD_NULLABLE)->collection();

            $this->added_columns->push($new);

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
         *
         * @return bool
         *
         * @throws Exception
         */
        public function append_column(string $name, string $type, int $size, bool $unique): bool
        {
            $driver = $this->driver;
            $data = collection();
            $command = "ALTER TABLE {$this->table} ADD COLUMN ";

            different($size,0) ?  append($command,"$name $type($size)") :  append($command,"$name $type ");

            $data->add($this->connexion->execute($command));


            if ($unique)
                $data->add($this->alter_table(Imperium::FIELD_UNIQUE,$name,$driver));


            return $data->not_exist(false);
        }


        /**
         *
         * @param string $constraint
         * @param string $column
         *
         * @param string $driver
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function alter_table(string $constraint,string $column,string  $driver)
        {
            if (equal($driver,Connect::MYSQL))
            {
                if (equal($constraint,Imperium::FIELD_UNIQUE))
                {
                    return $this->connexion->execute("ALTER TABLE $this->table ADD CONSTRAINT  UNIQUE($column)");
                }
            }

            if (equal($driver,Connect::POSTGRESQL))
            {
                if (equal($constraint,Imperium::FIELD_UNIQUE))
                {
                    return $this->connexion->execute("ALTER TABLE $this->table ADD UNIQUE ($column);");
                }
            }

            if (equal($driver,Connect::SQLITE))
            {
                if (equal($constraint,Imperium::FIELD_UNIQUE))
                {
                    return $this->connexion->execute("ALTER TABLE $this->table ADD UNIQUE ($column);");
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
            $driver = $this->driver;
            if (equal(Connect::POSTGRESQL,$driver))
                return $this->connexion->execute("ALTER TABLE {$this->table} ALTER COLUMN $column DROP $constraint;");

            return false;
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
                $end = has($column[Imperium::FIELD_NAME],$columns->last());
                append($command,$this->updateCreateCommand($column,$end));
            }


            append($command ,')');

            if ($this->connexion->mysql() && def($this->engine))
                append($command ," ENGINE = {$this->engine}");

            return $this->connexion->execute($command);

        }

        /**
         * start creation of a table
         *
         * @return string
         */
        public function startCreateCommand() : string
        {
            return $this->connexion->mysql() ? "CREATE TABLE IF NOT EXISTS `{$this->table}` ( " : "CREATE TABLE IF NOT EXISTS {$this->table} ( ";
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
        public function updateCreateCommand(array $field,bool $end) : string
        {
            $x = collection($field);

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

                if (!$end)
                    append($command,', ');

            return $command;
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
            return def($table) ? dumper($this->connexion,false,$table) : dumper($this->connexion,false,$this->table);
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

                    foreach ($this->connexion->request("show columns from {$this->table} where `Key` = 'PRI';") as $key)
                        return $key->Field;

                    break;

                case Connect::POSTGRESQL:

                    foreach($this->connexion->request ("select column_name FROM information_schema.key_column_usage WHERE table_name = '$this->table';") as $key)
                        return $key->column_name;
                    break;

                case Connect::SQLITE:

                    foreach ($this->connexion->request("PRAGMA table_info($this->table)") as $field)
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
            return empty($this->getRecords());

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
            return $this->connexion->request("SELECT * FROM {$this->table} WHERE {$this->get_primary_key()} = $id" );
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
            $data = $this->connexion->request("SELECT * FROM {$this->table} WHERE {$this->get_primary_key()} = $id" );


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
            return $this->connexion->execute("DELETE FROM {$this->table} WHERE {$this->get_primary_key()} = $id");
        }

        /**
         * @param string $table_name
         *
         * @param string ...$columns
         * @return bool
         *
         * @throws Exception
         */
        public function create_as_select(string $table_name,string ...$columns): bool
        {

            if (equal($this->driver,Connect::SQLITE) && def($this->table))
                return  $this->connexion->execute("CREATE TABLE $table_name AS SELECT $columns FROM $this->table");

            return false;
        }

        /**
         * @param string $table_name
         * @param string $tmp_table
         * @param bool $rename
         * @param string $old
         * @param string $new
         * @param string $glue
         * @return bool
         * @throws Exception
         */
        public function insert_as_select(string $table_name,string $tmp_table,bool $rename = false,$old = '',$new = '',string $glue = ', '): bool
        {

            if (equal($this->driver,Connect::SQLITE) && def($this->table))
                return  $rename ? $this->connexion->execute("INSERT INTO $table_name SELECT {$this->change_columns_name_to_string($old,$new,$glue)} FROM $tmp_table") : $this->connexion->execute("INSERT INTO $table_name SELECT {$this->columns_to_string()} FROM {$this->table}") ;
            return false;
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

            $type = $this->type($old);
            switch ($this->driver)
            {
                case Connect::MYSQL:

                    return equal($old,$this->get_primary_key()) ? false : $this->connexion->execute("ALTER TABLE {$this->table} CHANGE  $old $new $type ");
                break;
                case Connect::POSTGRESQL:

                    return equal($old,$this->get_primary_key()) ? false : $this->connexion->execute("ALTER TABLE {$this->table} RENAME COLUMN $old TO $new");
                break;


                default:
                    return false;
                break;
            }
        }


        /**
         * @param string $column
         *
         * @return bool
         *
         * @throws Exception
         */
        public function remove_column(string $column): bool
        {
            switch ($this->driver)
            {

                case Connect::MYSQL:
                    return equal($column,$this->get_primary_key()) ? false : $this->connexion->execute("ALTER TABLE $this->table DROP $column");
                break;
                case Connect::POSTGRESQL:
                    return equal($column,$this->get_primary_key()) ? false : $this->connexion->execute("ALTER TABLE $this->table DROP COLUMN $column RESTRICT");
                break;
                case Connect::SQLITE:

                    if (equal($column,$this->get_primary_key()))
                        return false;

                    $current = $this->table;

                    $columns =  collection($this->get_columns())->remove_values($column)->join(', ');

                    $new = "_$current";

                    $this->rename($new);

                    $this->connexion->execute("CREATE TABLE $current AS SELECT $columns FROM $this->table");

                    $this->connexion->execute("INSERT INTO $current SELECT $columns FROM $this->table");

                    return $this->set_current_table($current)->drop($new);

                break;
                default:
                    return false;
                break;

            }
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
        public function insert(array $values,array $toIgnore = [],string $table = ''): bool
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
                                $val->push("'".addslashes($value)."'");
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
         * set tables to ignore
         *
         * @param array $tables
         *
         * @return Table
         */
        public function ignore(array $tables): Table
        {
            $this->hidden = $tables;

            return $this;
        }

        /**
         * set the dump directory path
         *
         * @param string $path
         *
         * @return Table
         */
        public function path(string $path): Table
        {
            $this->path = $path;

            return $this;
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
            if (def($this->hidden))
                $hidden = collection($this->hidden);
            else
                $hidden = collection();

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
         * count number of records in a or multiples tables
         *
         * @param string|null $table
         * @param int $mode
         *
         * @return array|int
         * 
         * @throws Exception
         *
         */
        public function count(string $table = '',int $mode = Imperium::MODE_ONE_TABLE)
        {


            $x = def($table) ? $table :  $this->table;
            switch ($mode)
            {
                case Imperium::MODE_ONE_TABLE:
                    foreach ($this->connexion->request("SELECT COUNT(*) FROM {$x}") as $number)
                        return current($number);
                break;
                case Imperium::MODE_ALL_TABLES:
                    $numbers = collection();
                    foreach ($this->show() as $table)
                    {
                        foreach ($this->connexion->request("SELECT COUNT(*) FROM $table") as $number)
                        {
                            $numbers->merge([$table => current($number)]);
                        }
                    }
                    return $numbers->collection();
                break;
                default:
                    return '';
                break;
            }
            return '';
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
        public function truncateSqliteTable(string $table) : bool
        {
            $fields = collection($this->get_columns());
            $types = collection($this->get_columns_types());
            $primary = $this->get_primary_key();

            $end = $fields->last();

            $this->drop($table);


            $query = "CREATE TABLE $table ( ";

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
        public function getRecords(string $orderBy = 'desc'): array
        {
            return def($orderBy) ? $this->connexion->request("SELECT * FROM {$this->table} ORDER BY {$this->get_primary_key()} $orderBy") : $this->connexion->request("SELECT * FROM {$this->table}");
        }

        /**
         * count all table in current database
         *
         * @return int
         * 
         * @throws Exception
         */
        public function countTable(): int
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
                if ($k != $primary)
                {
                    if ($ignoreValues->empty())
                    {
                        if ($columns->numeric($value))
                                $columns->push("$k = $value");
                        else
                            $columns->push("$k = ' ".addslashes($value)."'");

                    }else {
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
         * optimize a table
         *
         * @return bool
         *
         * @throws Exception
         */
        public function optimize(): bool
        {
            return def($this->table) ? $this->connexion->execute("OPTIMIZE {$this->table}") : false;
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
        public function modifyColumn(string $column,string $type,int $size = 0): bool
        {
            switch ($this->driver)
            {
                case Connect::MYSQL:

                    if ($size)
                        return $this->connexion->execute("ALTER TABLE {$this->table} MODIFY $column $type($size)");
                    else
                        return $this->connexion->execute("ALTER TABLE {$this->table} MODIFY $column $type");

                break;
                case Connect::POSTGRESQL:

                    if ($size)
                        return $this->connexion->execute("ALTER TABLE {$this->table} ALTER COLUMN $column TYPE $type($size)");
                    else
                        return $this->connexion->execute("ALTER TABLE {$this->table} ALTER COLUMN $column TYPE $type");

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
                    if (!$this->drop($table))
                        return false;
            }else
            {
                foreach ($this->show() as $table)
                {
                    if ($hidden->not_exist($table))
                    {
                        if (!$this->drop($table))
                            return false;
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
            $table_columns = $instance->set_current_table($table)->get_columns();

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
        public function rename(string $new_name): bool
        {
            switch ($this->driver)
            {
                case Connect::MYSQL :
                    $data =  $this->connexion->execute("RENAME TABLE {$this->table} TO $new_name");
                    if ($data)
                        $this->table = $new_name;
                    return $data;
                break;
                case Connect::POSTGRESQL:
                    $data =   $this->connexion->execute("ALTER TABLE {$this->table} RENAME TO $new_name");
                    if ($data)
                        $this->table = $new_name;
                    return $data;
                break;
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
                        $types->push($type->Type);
                break;

                case Connect::POSTGRESQL:

                    foreach ($this->connexion->request("select data_type FROM information_schema.columns WHERE table_name ='$this->table';") as $type)
                        $types->push($type->data_type);

                break;

                case Connect::SQLITE:
                    foreach ($this->connexion->request("PRAGMA table_info($this->table)") as $type)
                        $types->push($type->type);
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

    }

}
