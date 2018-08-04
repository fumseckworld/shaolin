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


    use Imperium\Databases\Dumper\Tables\MySQLTable;
    use Imperium\Databases\Dumper\Tables\PostgreSQLTable;
    use Imperium\Databases\Dumper\Tables\SQLiteTable;
    use Imperium\Databases\Eloquent\Connexion\Connexion;
    use Imperium\Databases\Eloquent\Eloquent;
    use Imperium\File\File;
    use PDO;

    class Table extends Eloquent implements EloquentTableBuilder
    {


        /**
         * @var string
         */
        private $driver;

        /**
         * @var string
         */
        private $table;

        /**
         * @var string
         */
        private $new;

        /**
         * @var string
         */
        private $database;

        /**
         * @var string
         */
        private $username;

        /**
         * @var string
         */
        private $password;

        /**
         * @var array
         */
        private $columns = array();

        /**
         * @var string
         */
        private $path;

        /**
         * @var array
         */
        private $hidden;

        /**
         * @var int
         */
        private $fetch = PDO::FETCH_OBJ;

        /**
         * @var string
         */
        private $engine;

        /**
         * start table query builder
         *
         * @return Table
         */
        public static function manage(): Table
        {
            return new static();
        }

        /**
         * define name of table
         *
         * @param string $table
         *
         * @return Table
         */
        public function setName(string $table): Table
        {
            $this->table = $table;

            return $this;
        }

        /**
         * get driver
         *
         * @return string
         */
        public function getDriver(): string
        {
            return $this->driver;
        }
        /**
         * define new name of table
         *
         * @param string $newName
         *
         * @return Table
         */
        public function setNewName(string $newName): Table
        {
            $this->new = $newName;

            return $this;
        }

        /**
         * rename a table
         *
         * @return bool
         */
        public function rename(): bool
        {
            switch ($this->driver)
            {
                case Connexion::MYSQL :
                    return $this->exec("RENAME TABLE {$this->table} TO {$this->new}");
                break;
                case Connexion::POSTGRESQL:
                    return $this->exec("ALTER TABLE {$this->table} RENAME TO {$this->new}");
                break;
                case Connexion::SQLITE:
                    return $this->exec("ALTER TABLE {$this->table} RENAME TO {$this->new}");
                break;

                case Connexion::ORACLE:
                    return $this->exec("ALTER TABLE {$this->table} RENAME TO {$this->new}");
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
         */
        public function has(): bool
        {
            return !empty($this->show());
        }

        /**
         * check if table has a special column
         *
         * @param string $column
         *
         * @return bool
         */
        public function hasColumn(string $column): bool
        {
            return in_array($column,$this->getColumns());
        }



        /**
         * get columns types
         *
         * @return array
         */
        public function getColumnsTypes(): array
        {
            $types = array();

            switch ($this->driver)
            {
                case Connexion::MYSQL:

                    foreach ($this->request("SHOW FULL COLUMNS FROM $this->table") as $type)
                        push($types, $type->Type);

                break;

                case Connexion::POSTGRESQL:

                    foreach ($this->request("SELECT data_type FROM information_schema.columns WHERE table_name ='$this->table';") as $type)
                        push($types,$type->data_type);

                break;

                case Connexion::SQLITE:

                    foreach ($this->request("PRAGMA table_info($this->table)") as $type)
                         push($types, $type->type);

                break;

                case Connexion::ORACLE :

                    foreach ($this->request("SELECT data_type FROM user_tab_cols WHERE table_name = '$this->table'") as $field)
                         push($fields,$field->data_type);
                    break;
                default:
                    return $types;
                break;
            }
            return $types;
        }

        /**
         * get all columns in a table
         *
         * @return array
         */
        public function getColumns(): array
        {
            $fields = array();

            switch ($this->driver)
            {
                case Connexion::MYSQL :
                    foreach ($this->request("SHOW FULL COLUMNS FROM $this->table") as $column)
                        array_push($fields,$column->Field);
                break;

                case Connexion::POSTGRESQL :

                    foreach ($this->request("SELECT column_name FROM information_schema.columns WHERE table_name ='$this->table'") as $field)
                        array_push($fields, $field->column_name);

                break;

                case Connexion::SQLITE :

                    foreach ($this->request("PRAGMA table_info($this->table)") as $field)
                        array_push($fields,"$field->name");
                break;

                case Connexion::ORACLE :

                    foreach ($this->request("SELECT column_name FROM user_tab_cols WHERE table_name = '$this->table'") as $field)
                        array_push($fields,$field->column_name);
                break;
            }
            return $fields;
        }

        /**
         * delete a table
         *
         * @param string|null $table
         *
         * @return bool
         */
        public function drop(string $table = '' ): bool
        {

            if ($this->isOracle())
            {
                if (!empty($table))
                    return $this->exec("DROP TABLE $table CASCADE CONSTRAINT PURGE");
                else
                    return $this->exec("DROP TABLE {$this->table} CASCADE CONSTRAINT PURGE");
            }

            if (!empty($table) && $this->isMysql())
                return $this->exec("DROP TABLE `{$table}`");

            if (empty($table) && $this->isMysql())
                return $this->exec("DROP TABLE `{$this->table}`");

            if (!empty($table))
                return $this->exec("DROP TABLE $table");
            else
                return $this->exec("DROP TABLE {$this->table}");
        }

        /**
         * truncate one or all tables
         *
         * @param string|null $table
         * @param int         $mode
         *
         * @return bool
         */
        public function truncate(string $table = '',int $mode = Eloquent::MODE_ONE_TABLE): bool
        {
            if (!empty($table))
                $this->table = $table;

            if ($mode == Eloquent::MODE_ONE_TABLE)
            {
                switch ($this->driver)
                {
                    case Connexion::MYSQL :
                        return $this->exec("TRUNCATE TABLE {$this->table}");

                    case Connexion::POSTGRESQL :
                        return $this->exec("TRUNCATE TABLE {$this->table}  RESTART IDENTITY");
                    break;

                    case Connexion::SQLITE :
                        return $this->truncateSqliteTable($this->table);
                    break;
                    case Connexion::ORACLE :
                       return $this->exec("TRUNCATE TABLE {$this->table} DROP STORAGE");
                    break;
                }

            }
            if ($mode == Eloquent::MODE_ALL_TABLES)
            {
                foreach ($this->show() as $table)
                {
                    switch ($this->driver)
                    {
                        case Connexion::MYSQL :
                               if (!$this->exec("TRUNCATE TABLE $table"))
                                   return false;
                        break;

                        case Connexion::POSTGRESQL :
                              if (!$this->exec("TRUNCATE TABLE $table RESTART IDENTITY"))
                                  return false;
                        break;

                        case Connexion::ORACLE :
                              if (!$this->exec("TRUNCATE TABLE $table DROP STORAGE"))
                                  return false;
                        break;

                        case Connexion::SQLITE :
                              if (!$this->truncateSqliteTable($table))
                                  return false;
                        break;
                    }
                    return true;
                }
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
        public function addField(string $type, string $name, bool $primary = false, int $length = 0, bool $unique = true, bool $nullable = false): Table
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
         * @param int    $size
         * @param bool   $unique
         * @param bool   $nullable
         *
         * @return bool
         */
        public function addColumn(string $column, string $type, int $size = 0, bool $unique = true, bool $nullable = false): bool
        {
            $command = "ALTER TABLE {$this->table} ADD COLUMN ";

            if ($size)
                $command .=  " $column $type($size)";
            else
                $command .=  " $column $type";

           return $this->exec($command);
        }

        /**
         * create the table
         *
         * @return bool
         */
        public function create()
        {
            $command = $this->startCreateCommand();

            foreach ($this->columns as $field)
                $command .= $this->updateCreateCommand($field);

            $command .= ')';

            if ($this->isMysql() && !is_null($this->engine))
                $command .= " ENGINE = {$this->engine}";

            $this->columns = [];

            return $this->exec($command);
        }

        /**
         * start creation of a table
         *
         * @return string
         */
        protected function startCreateCommand() : string
        {
            if ($this->isMysql())
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

            switch ($this->driver)
            {
                case Connexion::MYSQL:

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

                case Connexion::POSTGRESQL:

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

                case Connexion::SQLITE:

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
                case Connexion::ORACLE;

                    foreach ($field as  $k => $v)
                    {
                        if ($k == Table::FIELD_NAME)
                            $command .= "$v";

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
        public function setHidden(array $hidden) : Table
        {
            $this->hidden = $hidden;

            return $this;
        }

        /**
         * check if current type id mysql
         *
         * @return bool
         */
        protected function isMysql() : bool
        {
            return $this->driver == Connexion::MYSQL;
        }
        /**
         * check if current type is postgresql
         *
         * @return bool
         */
        protected function isPostgresql() : bool
        {
            return $this->driver == Connexion::POSTGRESQL;
        }

        /**
         * check if current type is oracle
         *
         * @return bool
         */
        protected function isOracle()
        {
            return $this->driver == Connexion::ORACLE;
        }
        /**
         * check if current type is sqlite
         *
         * @return bool
         */
        protected function isSqlite() : bool
        {
            return $this->driver == Connexion::SQLITE;
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
         * @return mixed
         */
        public function dump(string $table = null)
        {
            if(empty($this->table) && is_null($table))
                return false;

            if (is_null($table))
                $file = "{$this->path}/{$this->table}.sql";
            else
                $file = "{$this->path}/$table.sql";

            switch ($this->driver)
            {
                case Connexion::MYSQL :
                    if (is_null($table))
                        MySQLTable::dump()->setTable($this->table)->setDbName($this->database)->setPassword($this->password)->setUserName($this->username)->dumpToFile($file,$this->path);
                    else
                        MySQLTable::dump()->setTable($table)->setDbName($this->database)->setPassword($this->password)->setUserName($this->username)->dumpToFile($file,$this->path);
                        File::download($file);
                break;
                case Connexion::POSTGRESQL :
                    if (is_null($table))
                        PostgreSQLTable::dump()->setTable($this->table)->setDbName($this->database)->setPassword($this->password)->setUserName($this->username)->dumpToFile($file,$this->path);
                    else
                        PostgreSQLTable::dump()->setTable($table)->setDbName($this->database)->setPassword($this->password)->setUserName($this->username)->dumpToFile($file,$this->path);
                        File::download($file);
                break;
                case Connexion::SQLITE:
                    if (is_null($table))
                        SQLiteTable::dump()->setTable($this->table)->setDbName($this->database)->dumpToFile( $file,$this->path);
                    else
                        SQLiteTable::dump()->setTable($table)->setDbName($this->database)->dumpToFile( $file,$this->path);
                        File::download($file);
                break;
            }
            return null;
        }

        /**
         * get the primary key of a table
         *
         * @return string|null
         */
        public function primaryKey()
        {
            switch ($this->driver)
            {
                case Connexion::MYSQL:

                    foreach ($this->request("show columns from {$this->table} where `Key` = 'PRI';") as $key)
                        return current($key);

                break;

                case Connexion::POSTGRESQL:

                    foreach($this->request (
                     "SELECT a.attname, format_type(a.atttypid, a.atttypmod) AS data_type
                              FROM pg_index i
                              JOIN pg_attribute a ON a.attrelid = i.indrelid
                              AND a.attnum = ANY(i.indkey)
                              WHERE  i.indrelid = '{$this->table}'::regclass AND i.indisprimary;") as $key
                    )
                    return current($key);
                break;

                case Connexion::SQLITE:

                    foreach ($this->request("PRAGMA table_info($this->table)") as $field)
                    {
                        if ($field->pk)
                            return $field->name;
                    }

                break;
                case Connexion::ORACLE:
                    foreach ($this->request

                        ("SELECT cols.table_name, cols.column_name, cols.position, cons.status, cons.owner 
                                    FROM all_constraints cons, all_cons_columns cols
                                    WHERE cols.table_name = UPPER({$this->table})
                                    AND cons.constraint_type = 'P'
                                    AND cons.constraint_name = cols.constraint_name
                                    AND cons.owner = cols.owner
                                    ORDER BY cols.table_name, cols.position;") as $key
                        )
                    {
                        return current($key);
                    }
                break;
            }
            return null;
        }

        /**
         * check if a table is empty
         *
         * @return bool
         */
        public function isEmpty(): bool
        {
            return empty($this->getRecords());
        }

        /**
         * select a record by id
         *
         * @param int $id
         *
         * @return array
         */
        public function selectById(int $id): array
        {
            return $this->request("SELECT * FROM {$this->table} WHERE {$this->primaryKey()} = $id" );
        }

        /**
         * delete a record by id
         *
         * @param int $id
         *
         * @return bool
         */
        public function deleteById(int $id): bool
        {
            return $this->exec("DELETE FROM {$this->table} WHERE {$this->primaryKey()} = $id");
        }

        /**
         * rename a existing column
         *
         * @param string $old
         * @param string $new
         *
         * @return bool
         */
        public function renameColumn(string $old, string $new): bool
        {
            switch ($this->driver)
            {
                case Connexion::MYSQL:
                    if ($old != $this->primaryKey())
                        return $this->exec("ALTER TABLE $this->table RENAME $old TO $new");
                    else
                        return false;
                break;

                case Connexion::POSTGRESQL:
                    if ($old != $this->primaryKey())
                        return $this->exec("ALTER TABLE $this->table RENAME COLUMN $old TO $new");
                    else
                        return false;
                break;

                case Connexion::ORACLE:
                    if ($old != $this->primaryKey())
                        return $this->exec("ALTER TABLE $this->table RENAME COLUMN $old TO $new");
                    else
                        return false;
                break;

                case Connexion::SQLITE:
                    $newColumns = array();
                    $columns = $this->getColumns();
                    $types = $this->getColumnsTypes();
                    $tmp = "_$this->table";

                    foreach ($columns as $k => $column)
                    {
                        switch ($column)
                        {
                            case $this->primaryKey():
                                if ($column == $old)
                                    array_push($newColumns," '$new' $types[$k] PRIMARY KEY AUTOINCREMENT ");
                                else
                                    array_push($newColumns," '$column' $types[$k] PRIMARY KEY AUTOINCREMENT ");
                            break;
                            case $old:
                                array_push($newColumns," '$new' $types[$k] ");
                            break;
                            default:
                                array_push($newColumns," '$column' $types[$k] ");
                            break;
                        }

                    }

                    $columns = join(', ',$newColumns);

                    $query = "CREATE TABLE IF NOT EXISTS $this->table (";
                    $query .= $columns;
                    $query .= ')';


                     $this->setNewName($tmp)->rename();
                     $this->exec($query);
                     $this->exec("INSERT INTO $this->table SELECT * FROM '$tmp'") ;
                     return $this->drop($tmp);
                break;
            }
            return false;
        }

        /**
         * delete a column
         *
         * @param string $column
         *
         * @return bool
         */
        public function deleteColumn(string $column): bool
        {
            switch ($this->driver)
            {
                case Connexion::MYSQL:
                    return $this->exec("ALTER TABLE $this->table DROP $column");
                break;
                case Connexion::POSTGRESQL:
                    return $this->exec("ALTER TABLE $this->table DROP COLUMN $column RESTRICT");
                break;
                case Connexion::ORACLE:
                    return $this->exec("ALTER TABLE $this->table DROP COLUMN $column");
                break;
                case Connexion::SQLITE:

                    $new = md5($this->table);
                    $origin = $this->getColumns();
                    $types = $this->getColumnsTypes();
                    $fields = array();

                    foreach ($origin as $k => $value)
                    {
                        switch ($value)
                        {
                            case $column: // do nothing if is the column to delete it
                            break;
                            case $this->isEnd($value):
                                array_push($fields,"$origin[$k] $types[$k]");
                            break;
                            default:
                                array_push($fields,"$origin[$k] $types[$k],");
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
                    $old = implode(' ',$origin);

                    $this->exec("ALTER TABLE $this->table  RENAME TO $new;");
                    $this->exec($query);
                    $this->exec("INSERT INTO $this->table ($tableFields) SELECT $old FROM $new");
                    return $this->exec("DROP TABLE $new");
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
         */
        public function exist(string $table = ""): bool
        {
            if (!empty($table))
                return has($table,$this->show());
            else
                return has($this->table,$this->show());
        }

        /**
         * insert values
         *
         * @param array       $values
         * @param array       $toIgnore
         * @param string|null $table
         *
         * @return bool
         */
        public function insert(array $values,array $toIgnore = [],string $table = null): bool
        {
            if (!is_null($table))
                $this->table = $table;

            $columns = join(', ',$this->getColumns());

            switch ($this->driver)
            {
                case Connexion::MYSQL:
                    $val = array();

                    foreach ($values as $key => $value)
                    {
                        if (!in_array($value,$toIgnore))
                        {
                            if (empty($value))
                            {
                                array_push($val,'NULL');
                            }else {
                                if (is_numeric($value))
                                    array_push($val,$value);
                                else
                                    array_push($val,"'".addslashes($value)."'");
                            }
                        }
                    }

                    $value = '(' . join(', ',$val) .')';
                    $command = "INSERT INTO `$this->table` ($columns) VALUES $value";

                    return $this->exec($command);
                break;

                case Connexion::POSTGRESQL:

                    $val = array();
                    foreach ($values as $key => $value)
                    {
                        if (!in_array($value,$toIgnore))
                        {
                            if (empty($value))
                            {
                                 array_push($val,'DEFAULT');
                            }else{
                                if (is_numeric($value))
                                    array_push($val,$value);
                                else
                                    array_push($val,"'".addslashes($value)."'");

                            }
                        }
                    }

                    $value = '(' . join(', ',$val) .')';
                    $command = "INSERT INTO $this->table ($columns) VALUES $value";
                    return $this->exec($command);
                break;

                case Connexion::SQLITE:

                    $val = array();

                    foreach ($values as $key => $value)
                    {
                        if (!in_array($value,$toIgnore))
                        {
                            if (empty($value))
                            {
                                array_push($val,'NULL');
                            }else {
                                if (is_numeric($value))
                                    array_push($val,$value);
                                else
                                    array_push($val,"'".addslashes($value)."'");
                            }
                        }
                    }

                    $value = '(' . join(', ',$val) .')';
                    $command = "INSERT INTO $this->table ($columns) VALUES $value";
                    return $this->exec($command);
                break;
                default:
                    return false;
                break;
            }
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
        public function setDumpPath(string $path): Table
        {
            $this->path = $path;

            return $this;
        }

        /**
         * show all tables in a database
         *
         * @return array
         */
        public function show(): array
        {
            $tables = array();
            switch ($this->driver)
            {
                case Connexion::MYSQL :

                    foreach ($this->request("SHOW TABLES") as $table)
                    {
                        if (!empty($this->hidden))
                        {
                            if (!has(current($table),$this->hidden))
                                push($tables,current($table));
                        } else {
                            push($tables,current($table));
                        }
                    }
                break;

                case Connexion::POSTGRESQL:

                    foreach ($this->request("SELECT table_schema || '.' || table_name FROM information_schema.tables WHERE  table_type = 'BASE TABLE' AND table_schema NOT IN ('pg_catalog', 'information_schema');") as $table)
                    {

                        if (!empty($this->hidden))
                        {
                            if (!has(current($table),$this->hidden))
                                push($tables,current($table));
                        } else {
                            push($tables,current($table));
                        }
                    }
                break;

                case Connexion::SQLITE:

                    foreach ($this->request("SELECT tbl_name FROM sqlite_master") as $table)
                    {
                        $t = $table->tbl_name;
                        if (!has($t,$tables))
                        {
                            if (!empty($this->hidden))
                            {
                                if (!has($t,$this->hidden))
                                {
                                    push($tables,$t);
                                }
                            }else{
                                push($tables,$t);
                            }
                        }

                    }

                break;

                case Connexion::ORACLE:
                    foreach ($this->request('SELECT table_name FROM user_tables') as $table)
                    {
                        if (!empty($this->hidden))
                        {
                            if (!has(current($table),$this->hidden))
                                push($tables,current($table));
                        } else {
                            push($tables,current($table));
                        }
                    }
                break;
            }

            return $tables;
        }

        /**
         * count number of records in a or multiples tables
         *
         * @param string|null $table
         * @param int         $mode
         *
         * @return array|int
         */
        public function count(string $table = '',int $mode = Eloquent::MODE_ONE_TABLE)
        {
            if (!empty($table) && $mode == Eloquent::MODE_ONE_TABLE)
            {
                foreach ($this->request("SELECT COUNT(*) FROM {$table}") as $number)
                    return current($number);
            }

            if ($mode == Eloquent::MODE_ONE_TABLE && ! empty($this->table))
            {
                foreach ($this->request("SELECT COUNT(*) FROM {$this->table}") as $number)
                    return current($number);

            }

            if ($mode == Eloquent::MODE_ALL_TABLES)
            {
                $numbers = array();

                foreach ($this->show() as $table)
                {
                    foreach ($this->request("SELECT COUNT(*) FROM $table") as $number)
                    {
                        $numbers = array_merge($numbers,[$table => current($number)]);
                    }

                }
                return $numbers;
            }
            return null;
        }

        /**
         * set the database driver
         *
         * @param string $driver
         *
         * @return Table
         */
        public function setDriver(string $driver): Table
        {
            $this->driver = $driver;

            return $this;
        }

        /**
         * set name of database
         *
         * @param string $database
         *
         * @return Table
         */
        public function setDatabase(string $database): Table
        {
            $this->database = $database;

            return $this;
        }

        /**
         * define database username
         *
         * @param string $username
         *
         * @return Table
         */
        public function setUsername(string $username): Table
        {
            $this->username = $username;

            return $this;
        }

        /**
         * define username password
         *
         * @param string $password
         *
         * @return Table
         */
        public function setPassword(string $password): Table
        {
            $this->password = $password;

            return $this;
        }

        /**
         * execute a statement
         *
         * @param string $statement
         *
         * @return bool
         */
        public function exec(string $statement): bool
        {

            if ($this->isSqlite())
            {
                if (empty($this->database))
                    $pdo = connect($this->driver);
                else
                    $pdo = connect($this->driver,$this->database);

                $request = $pdo->prepare($statement);

                return $request->execute();
            }

            $pdo = connect($this->driver,$this->database,$this->username,$this->password);
            if (is_null($pdo))
                return false;
            $request = $pdo->prepare($statement);

            return  $request->execute();

        }

        /**
         * execute a statement
         *
         * @param string $statement
         *
         * @return array
         */
        public function request(string $statement): array
        {
            $pdo = connect($this->driver,$this->database,$this->username,$this->password);

            if (is_null($pdo))
                return array();

            $request =  $pdo->prepare($statement);
            $request->execute();
            return $request->fetchAll($this->fetch);

        }

        /**
         * truncate a sqlite table
         *
         * @param string $table
         * @return bool
         */
        protected function truncateSqliteTable(string $table) : bool
        {
            $fields = $this->getColumns();
            $types  = $this->getColumnsTypes();
            $primary = $this->primaryKey();

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

            return $this->exec($query);
        }

        /**
         * get all record in a table
         *
         * @param string $orderBy
         *
         * @return array
         */
        public function getRecords(string $orderBy = ''): array
        {
            if (!empty($orderBy))
                return $this->request("SELECT * FROM {$this->table} ORDER BY {$this->primaryKey()} $orderBy");
            else
                return $this->request("SELECT * FROM {$this->table}");
        }

        /**
         * count all table in current database
         *
         * @return int
         */
        public function countTable(): int
        {
            return count($this->show());
        }

        /**
         * update a table
         *
         * @param int         $id
         * @param array       $values
         * @param array       $ignore
         * @param string|null $table
         *
         * @return bool
         */
        public function update(int $id,array $values,array $ignore= [],string $table = ''): bool
        {
            if (!empty($table))
                $this->table = $table;

            $primary = $this->primaryKey();
            $columns = array();

            foreach ($values  as $k => $value)
            {
                if ($k != $primary)
                {
                    if (empty($ignore))
                    {
                        if (is_numeric($value))
                            array_push($columns,"$k = $value");
                        else
                            array_push($columns,"$k = '".addslashes($value)."'");
                    }else {
                        if (!in_array($value,$ignore))
                        {
                            if (is_numeric($value))
                                array_push($columns,"$k = $value");
                            else
                                array_push($columns,"$k = '".addslashes($value)."'");
                        }
                    }
                }

            }



            $columns = join(' , ',$columns);

            $command = "UPDATE {$this->table} SET $columns  WHERE $primary = $id";
            return $this->exec($command);
        }


        /**
         * optimize a table
         *
         * @return bool
         */
        public function optimize(): bool
        {
            if (!empty($this->table))
                return $this->exec("OPTIMIZE {$this->table}");
            return
                false;
        }

        /**
         * modify an existing column
         *
         * @param string $column
         * @param string $type
         * @param int    $size
         *
         * @return bool
         */
        public function modifyColumn(string $column,string $type,int $size = 0): bool
        {
            switch ($this->driver)
            {
                case Connexion::MYSQL:

                    if ($size)
                        return $this->exec("ALTER TABLE {$this->table} MODIFY $column $type($size)");
                    else
                        return $this->exec("ALTER TABLE {$this->table} MODIFY $column $type");

                break;
                case Connexion::POSTGRESQL:

                    if ($size)
                        return $this->exec("ALTER TABLE {$this->table} ALTER COLUMN $column TYPE $type($size)");
                    else
                        return $this->exec("ALTER TABLE {$this->table} ALTER COLUMN $column TYPE $type");

                break;
                case Connexion::ORACLE:

                    if ($size)
                        return $this->exec("ALTER TABLE {$this->table} MODIFY $column $type($size)");
                    else
                        return $this->exec("ALTER TABLE {$this->table} MODIFY $column $type");

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

            if ($this->isMysql() && !is_null($this->engine))
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
        public function setEngine(string $engine): Table
        {
            $this->engine = $engine;

            return $this;
        }

        /**
         * delete all tables not ignored in database
         *
         * @return bool
         */
        public function dropAll(): bool
        {
            foreach ($this->show() as $table)
                if (!$this->drop($table))
                    return false;

            return true;
        }


        /**
         * appends columns in an existing table
         *
         * @param string $table
         * @param Table $instance
         * @param array $newColumnNames
         * @param array $newColumnsTypes
         * @param array $newColumnsLength
         * @param array $newColumnOrder
         * @param array $existingColumnsSelected
         * @param array $unique
         * @param array $nullable
         *
         * @return bool
         */
        public function appendColumns(string $table, Table $instance, array $newColumnNames, array $newColumnsTypes, array $newColumnsLength, array $newColumnOrder, array $existingColumnsSelected, array $unique, array $nullable): bool
        {
            $tableColumns = $instance->setName($table)->getColumns();
            $theEndColumn =  end($newColumnNames);

            switch ($this->getDriver())
            {
                case Connexion::MYSQL:
                    $command = "ALTER TABLE `$table`  ";

                    for ($i=0;$i<count($newColumnNames);$i++)
                    {
                        $columnName     = $newColumnNames[$i];
                        $columnType     = $newColumnsTypes[$i];
                        $columnLength   = $newColumnsLength[$i];
                        $columnSelected = $existingColumnsSelected[$i];

                        $isFirst        = $newColumnOrder[$i] == 'FIRST';
                        $isUnique       = $unique[$i] == true;
                        $isNullable     = $nullable[$i] == true;
                        $islength       = !empty($columnLength);
                        $isTheEnd       = $newColumnNames[$i] === $theEndColumn;



                        $columnPrev = array_prev($tableColumns,$columnSelected);

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
                    return $this->exec($command);
                break;
                default:
                    return false;
                break;
            }


        }
    }

}