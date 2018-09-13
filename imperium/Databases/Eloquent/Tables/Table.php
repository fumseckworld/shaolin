<?php
/**
 * fumseck added Builder.php to imperium
 * The 09/09/17 at 19:39
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package : imperium
 * @author  : fumseck
 */


namespace  Imperium\Databases\Eloquent\Tables {

    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\Databases\Eloquent\Eloquent;
    use Imperium\Databases\Eloquent\Share;


    class Table extends Eloquent implements EloquentTableBuilder
    {

        use Share;
        
        /**
         * new table name
         * 
         * @var string
         */
        private $new;

        /**
         * @var array
         */
        private $columns;

        /**
         * Mysql engine
         *
         * @var string
         */
        private $engine;




        /**
         * define name of table
         *
         * @param string $current_table_name
         *
         * @return Table
         */
        public function set_current_table(string $current_table_name): Table
        {
            $this->table = $current_table_name;

            return $this;
        }

        /**
         * define new name of table
         *
         * @param string $new_name
         *
         * @return Table
         */
        public function set_new_name(string $new_name): Table
        {
            $this->new = $new_name;

            return $this;
        }

        /**
         * Table constructor.
         * @param Connect $connect
         *
         * @throws Exception
         */
        public function __construct(Connect $connect)
        {
             $this->connexion =  $connect;
        }  
        
        /**
         * rename a table
         *
         * @return bool
         * 
         * @throws Exception
         */
        public function rename(): bool
        {
            switch ($this->connexion->get_driver())
            {
                case Connect::MYSQL :
                    return $this->connexion->execute("RENAME TABLE {$this->table} TO {$this->new}");
                break;
                case Connect::POSTGRESQL:
                    return $this->connexion->execute("ALTER TABLE {$this->table} RENAME TO {$this->new}");
                break;
                case Connect::SQLITE:
                    return $this->connexion->execute("ALTER TABLE {$this->table} RENAME TO {$this->new}");
                break;
                default:
                    return false;
                break;
            }
        }

        /**
         * check if current database has table
         *
         * @return bool
         * 
         * @throws Exception
         */
        public function has(): bool
        {
            return def($this->show());
        }

        /**
         * check if table has a special column
         *
         * @param string $column
         *
         * @return bool
         * 
         * @throws Exception
         */
        public function has_column(string $column): bool
        {
            return collection($this->get_columns())->exist($column);
        }

        /**
         * get columns types
         *
         * @return array
         * 
         * @throws Exception
         */
        public function get_columns_types(): array
        {
            $types = collection();

            switch ($this->connexion->get_driver())
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
            return $types->getCollection();
        }

        /**
         * get all columns in a table
         *
         * @return array
         *
         * @throws Exception
         */
        public function get_columns(): array
        {
            $fields = collection();

            switch ($this->connexion->get_driver())
            {
                case Connect::MYSQL :

                    foreach ($this->connexion->request("SHOW FULL COLUMNS FROM $this->table") as $column)
                        $fields->push($column->Field);

                break;

                case Connect::POSTGRESQL :

                    foreach ($this->connexion->request("SELECT column_name FROM information_schema.columns WHERE table_name ='$this->table'") as $field)
                       $fields->push($field->column_name);

                break;

                case Connect::SQLITE :
                    foreach ($this->connexion->request("PRAGMA table_info($this->table)") as $field)
                        $fields->push($field->name);
                break;
            }

            return $fields->getCollection();
        }

        /**
         * delete a table
         *
         * @param string|null $table
         *
         * @return bool
         *
         * @throws Exception
         */
        public function drop(string $table = '' ): bool
        {
            return def($table) ? $this->connexion->execute("DROP TABLE $table") :  $this->connexion->execute("DROP TABLE {$this->table}");
        }

        /**
         * truncate one or all tables
         *
         * @param string|null $table
         * @param int $mode
         *
         * @return bool
         * 
         * @throws Exception
         */
        public function truncate(string $table = '',int $mode = Eloquent::MODE_ONE_TABLE): bool
        {
            if (def($table))
                $this->table = $table;

            if ($mode == Eloquent::MODE_ONE_TABLE)
            {
                switch ($this->connexion->get_driver())
                {
                    case Connect::MYSQL :
                        return $this->connexion->execute("TRUNCATE TABLE {$this->table}");

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
            if ($mode == Eloquent::MODE_ALL_TABLES)
            {
                foreach ($this->show() as $table)
                {
                    switch ($this->connexion->get_driver())
                    {
                        case Connect::MYSQL :
                               if (!$this->connexion->execute("TRUNCATE TABLE $table"))
                                   return false;
                        break;

                        case Connect::POSTGRESQL :
                              if (!$this->connexion->execute("TRUNCATE TABLE $table RESTART IDENTITY"))
                                  return false;
                        break;
                        case Connect::SQLITE :
                              if (!$this->truncateSqliteTable($table))
                                  return false;
                        break;
                    }

                }
                return true;
            }
            return false;
        }

        /**
         * add a new field in create task
         *
         * @param string $type
         * @param string $name
         * @param bool   $primary
         * @param int    $length
         * @param bool   $unique
         * @param bool   $nullable
         *
         * @return Table
         */
        public function append_field(string $type, string $name, bool $primary = false, int $length = 0, bool $unique = true, bool $nullable = false): Table
        {
            if (empty($this->columns))
            {
                $this->columns[] =  [Table::FIELD_NAME => $name ,Table::FIELD_TYPE => $type,Table::FIELD_PRIMARY=> $primary,Table::FIELD_LENGTH => $length,Table::FIELD_UNIQUE => $unique,Table::FIELD_NULLABLE => $nullable];
            } else {
                $new[] =  [Table::FIELD_NAME => $name ,Table::FIELD_TYPE => $type,Table::FIELD_PRIMARY=> $primary,Table::FIELD_LENGTH => $length,Table::FIELD_UNIQUE => $unique,Table::FIELD_NULLABLE => $nullable];
                $this->columns  = array_merge($this->columns,$new);
            }

            return $this;

        }

        /**
         * add a column in a existing table
         *
         * @param string $column
         * @param string $type
         * @param int $size
         * @param bool $unique
         * @param bool $nullable
         *
         * @return bool
         * 
         * @throws Exception
         */
        public function append_column(string $column, string $type, int $size = 0, bool $unique = true, bool $nullable = false): bool
        {
            $command = "ALTER TABLE {$this->table} ADD COLUMN ";

            if ($size)
                $command .=  " $column $type($size)";
            else
                $command .=  " $column $type";

           return $this->connexion->execute($command);
        }

        /**
         * create the table
         *
         * @return bool
         * 
         * @throws Exception
         */
        public function create(): bool 
        {
            $command = $this->startCreateCommand();

            foreach ($this->columns as $field)
                $command .= $this->updateCreateCommand($field);

            $command .= ')';

            if ($this->connexion->mysql() && !is_null($this->engine))
                $command .= " ENGINE = {$this->engine}";

            $this->columns = [];

            return $this->connexion->execute($command);
        }

        /**
         * start creation of a table
         *
         * @return string
         */
        protected function startCreateCommand() : string
        {
            if ($this->connexion->mysql())
                return "CREATE TABLE IF NOT EXISTS `{$this->table}` ( ";
            else
                return  "CREATE TABLE IF NOT EXISTS {$this->table} ( ";
        }

        /**
         * append field in command to create table
         *
         * @param array $field
         * @return string
         */
        protected function updateCreateCommand(array $field) : string
        {

            $size = $field[Table::FIELD_LENGTH];

            $command = '';

            switch ($this->connexion->get_driver())
            {
                case Connect::MYSQL:

                    foreach ($field as  $k => $v)
                    {
                        if ($k == Table::FIELD_NAME)
                            $command .= "`$v`";

                        if ($k == Table::FIELD_TYPE)
                        {
                            if ($size)
                                $command .= " $v($size)";
                            else
                                $command .= " $v";
                        }


                        if ($k == Table::FIELD_PRIMARY)
                        {
                            if ($v)
                                $command .= ' AUTO_INCREMENT PRIMARY KEY';
                        }

                        if ($k == Table::FIELD_UNIQUE)
                        {

                            if ($v)
                                $command .= " UNIQUE";
                        }

                        if ($k == Table::FIELD_NULLABLE)
                        {
                            if (!$v)
                                $command .= " NOT NULL";
                        }


                    }
                    if (!$this->isEnd($field))
                        $command .= ', ';

                break;

                case Connect::POSTGRESQL:

                    foreach ($field as  $k => $v)
                    {
                        if ($k == Table::FIELD_NAME)
                            $command .= "$v";

                        if ($k == Table::FIELD_TYPE)
                        {
                            if ($field[Table::FIELD_LENGTH])
                                $command .= " $v($size)";
                            else
                                $command .= " $v";
                        }


                        if ($k == Table::FIELD_PRIMARY)
                        {
                            if ($v)
                                $command .= ' PRIMARY KEY';
                        }

                        if ($k == Table::FIELD_UNIQUE)
                        {
                            if ($v)
                                $command .= " UNIQUE";
                        }

                        if ($k == Table::FIELD_NULLABLE)
                        {
                            if (!$v)
                                $command .= " NOT NULL";
                        }
                    }

                    if (!$this->isEnd($field))
                        $command .= ', ';

                break;

                case Connect::SQLITE:

                    foreach ($field as  $k => $v)
                    {
                        if ($k == Table::FIELD_NAME)
                            $command .= "'$v'";

                        if ($k == Table::FIELD_TYPE)
                        {
                            if ($size)
                                $command .= " $v($size)";
                            else
                                $command .= " $v";
                        }


                        if ($k == Table::FIELD_PRIMARY)
                        {
                            if ($v)
                                $command .= ' PRIMARY KEY AUTOINCREMENT';
                        }

                        if ($k == Table::FIELD_UNIQUE)
                        {
                            if ($v)
                                $command .= " UNIQUE";
                        }

                        if ($k == Table::FIELD_NULLABLE)
                        {
                            if (!$v)
                                $command .= " NOT NULL";
                        }
                    }

                    if (!$this->isEnd($field))
                        $command .= ', ';
                break;
            }
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
         * return the last field name
         *
         * @param array $fields
         * @return string
         */
        protected function end(array $fields) : string
        {
            $fieldsKeys = array_keys($fields);
            $end = end($fieldsKeys);
            foreach ($fields as $k => $field)
            {
                if($k == $end)
                    return $field[Table::FIELD_NAME];
            }
            return '';
        }
        /**
         * check if is the end of fields
         *
         * @param $field
         * @return bool
         */
        protected function isEnd($field) : bool
        {
            if (is_array($field))
                return $this->end($this->columns) == $field[Table::FIELD_NAME];
            else
                return $this->end($this->columns) == $field ;
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
            switch ($this->connexion->get_driver())
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
                        if ($field->pk)
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

            if (is_null($primary))
                throw new Exception('We have not found a primary key');
            else
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
         * delete a record by id
         *
         * @param int $id
         *
         * @return bool
         *
         * @throws Exception
         */
        public function delete_by_id(int $id): bool
        {
            return $this->connexion->execute("DELETE FROM {$this->table} WHERE {$this->get_primary_key()} = $id");
        }

        /**
         * rename a existing column
         *
         * @param string $old
         * @param string $new
         *
         * @return bool
         *
         * @throws Exception
         */
        public function rename_column(string $old, string $new): bool
        {
            switch ($this->connexion->get_driver())
            {
                case Connect::MYSQL:
                case Connect::POSTGRESQL:
                    return $old === $this->get_primary_key() ? false : $this->connexion->execute("ALTER TABLE $this->table RENAME $old TO $new");
                break;
                case Connect::SQLITE:
                    $newColumns = collection();
                    $columns = $this->get_columns();
                    $types = $this->get_columns_types();
                    $tmp = "_$this->table";

                    foreach ($columns as $k => $column)
                    {
                        switch ($column)
                        {
                            case $this->get_primary_key():
                                $column == $old ? $newColumns->push("'$new' $types[$k] PRIMARY KEY AUTOINCREMENT ") : $newColumns->push(" '$column' $types[$k] PRIMARY KEY AUTOINCREMENT ");
                            break;
                            case $old:
                                $newColumns->push(" '$new' $types[$k] ");
                            break;
                            default:
                                $newColumns->push(" '$column' $types[$k] ");
                            break;
                        }

                    }

                    $columns = $newColumns->join(', ');

                    $query = "CREATE TABLE IF NOT EXISTS $this->table (";
                    $query .= $columns;
                    $query .= ')';


                     $this->set_new_name($tmp)->rename();
                     $this->connexion->execute($query);
                     $this->connexion->execute("INSERT INTO $this->table SELECT * FROM '$tmp'") ;
                     return $this->drop($tmp);
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
        public function deleteColumn(string $column): bool
        {
            switch ($this->connexion->get_driver())
            {

                case Connect::MYSQL:
                    return equal($column,$this->get_primary_key()) ? false : $this->connexion->execute("ALTER TABLE $this->table DROP $column");
                break;
                case Connect::POSTGRESQL:
                    return equal($column,$this->get_primary_key()) ? false : $this->connexion->execute("ALTER TABLE $this->table DROP COLUMN $column RESTRICT");
                break;
                case Connect::SQLITE:

                    $new = md5($this->table);
                    $origin = collection($this->get_columns());
                    $types = collection($this->get_columns_types());
                    $fields = collection();

                    foreach ($origin as $k => $value)
                    {
                        switch ($value)
                        {
                            case $column: // do nothing if is the column to delete it
                            break;
                            case $this->isEnd($value):
                                $fields->push("$origin[$k] $types[$k]");
                            break;
                            default:
                                $fields->push("$origin[$k] $types[$k] ,");
                            break;
                        }
                    }

                    $query = "CREATE TABLE IF NOT EXISTS  $this->table (";
                    $tableFields = '';
                    foreach ($fields as $item)
                    {
                        $query .= $item;
                        $tableFields .= $item;
                    }

                    $query .= ')';

                    $old = $origin->join(' ');

                    $this->connexion->execute("ALTER TABLE $this->table  RENAME TO $new;");
                    $this->connexion->execute($query);
                    $this->connexion->execute("INSERT INTO $this->table ($tableFields) SELECT $old FROM $new");
                    return $this->connexion->execute("DROP TABLE $new");
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

                    switch ($this->connexion->get_driver())
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

            switch ($this->connexion->get_driver())
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
            return $tables->getCollection();
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
         */
        public function count(string $table = '',int $mode = Eloquent::MODE_ONE_TABLE)
        {
            if (def($table) && $mode == Eloquent::MODE_ONE_TABLE)
            {
                foreach ($this->connexion->request("SELECT COUNT(*) FROM {$table}") as $number)
                    return current($number);
            }

            if ($mode == Eloquent::MODE_ONE_TABLE && ! empty($this->table))
            {
                foreach ($this->connexion->request("SELECT COUNT(*) FROM {$this->table}") as $number)
                    return current($number);

            }

            if ($mode == Eloquent::MODE_ALL_TABLES)
            {
                $numbers = collection();

                foreach ($this->show() as $table)
                {
                    foreach ($this->connexion->request("SELECT COUNT(*) FROM $table") as $number)
                    {
                        $numbers->merge([$table => current($number)]);
                    }
                }
                return $numbers->getCollection();
            }
            return null;
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
        protected function truncateSqliteTable(string $table) : bool
        {
            $fields = $this->get_columns();
            $types  = $this->get_columns_types();
            $primary = $this->get_primary_key();

            $end = end($fields);
            $this->drop($table);


            $query = "CREATE TABLE $table ( ";

            foreach ($fields as $k => $field)
            {
                switch ($field)
                {
                    case $primary:
                        if ($field == $end)
                            $query .= " '$primary' $types[$k] PRIMARY KEY AUTOINCREMENT";
                        else
                            $query .= " '$primary' $types[$k] PRIMARY KEY AUTOINCREMENT ,";
                    break;
                    default:
                        if ($field == $end)
                            $query .= " '$field' $types[$k]";
                        else
                            $query .= " '$field' $types[$k] ,";
                    break;
                }

            }
            $query .= ')';

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
            switch ($this->connexion->get_driver())
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
         * return create table sql query
         *
         * @return string
         */
        public function get(): string
        {
            $command = $this->startCreateCommand();

            foreach ($this->columns as $column)
                $command .= $this->updateCreateCommand($column);

            $command .= ')';

            if ($this->connexion->mysql() && !is_null($this->engine))
                $command .= " ENGINE = $this->engine";

            $this->columns = [];

            return $command;
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
         * @param array $new_columns_length
         * @param array $new_column_order
         * @param array $existing_columns_selected
         * @param array $unique
         * @param array $null
         *
         * @return bool
         *
         * @throws Exception
         */
        public function append_columns(string $table, Table $instance, array  $new_columns_names, array $new_columns_types, array $new_columns_length, array $new_column_order, array $existing_columns_selected, array $unique, array $null): bool
        {
            $table_columns = $instance->set_current_table($table)->get_columns();

            $the_end_of_new_columns =  end($new_columns_names);

            switch ($this->connexion->get_driver())
            {
                case Connect::MYSQL:

                    $command = "ALTER TABLE `$table`  ";


                    for ($i=0;$i<count($new_columns_names);$i++)
                    {
                        $columnName     = $new_columns_names[$i];
                        $columnType     = $new_columns_types[$i];
                        $columnLength   = $new_columns_types[$i];
                        $columnSelected = $existing_columns_selected[$i];

                        $isFirst        = $new_column_order[$i] == 'FIRST';
                        $isUnique       = $unique[$i] == true;
                        $isNullable     = $null[$i] == true;
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
    }

}