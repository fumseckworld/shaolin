<?php
declare(strict_types=1);

namespace Eywa\Database\Table {


    use Eywa\Database\Connexion\Connect;
    use Eywa\Exception\Kedavra;
    use wapmorgan\FileTypeDetector\Detector;


    class Table
    {
        /**
         *
         * The table name
         *
         */
        private ?string $table = null;

        /**
         *
         * The connexion
         *
         */
        private Connect $connexion;

        /**
         *
         * The primary key of the table
         *
         */
        private ?string $primary = null;

        /**
         *
         * The table columns
         *
         */
        private array $columns = [];

        private string $saved_table = '';


        /**
         *
         * Table constructor.
         *
         * @param Connect $connect
         */
        public function __construct(Connect $connect)
        {

            $this->connexion = $connect;

        }

        /**
         *
         * Select a table
         *
         * @param string $table
         *
         * @return Table
         *
         */
        public function from(string $table): Table
        {
            $this->table = $table;

            return $this;
        }

        /**
         *
         *
         * @param int $records
         * @param callable $callable
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function seed(int $records, callable $callable): bool
        {
            $this->check();

            $seed = collect();

            for ($i = 0; $i != $records; $i++)
            {
                $x = call_user_func_array($callable, [faker(config('lang', 'locale')), $this]);

                is_false(is_string($x),true,"The return value must be a string");

                $seed->push($x);

            }
            $columns = collect($this->columns())->join();
           return $this->connexion->set("INSERT INTO {$this->table} ({$columns}) VALUES  {$seed->join()}")->execute();

        }

        /**
         *
         * Check if the table exist
         *
         * @param string $table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function exist(string $table): bool
        {
            return collect($this->show())->exist($table);
        }


        /**
         *
         * Add secure quote
         *
         * @param string $x
         *
         * @return string
         *
         *
         */
        public function quote(string $x): string
        {
            return $this->connexion->secure($x);
        }

        /**
         *
         * Check if a column exist
         *
         * @param string[] $columns
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function has(string ...$columns): bool
        {
            $boolean = collect();
            foreach ($columns as $column)
                $boolean->push(collect($this->columns())->exist($column));

            return $boolean->ok();
        }

        /**
         *
         * Rename a table
         *
         * @param string $new_name
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function rename(string $new_name): bool
        {

            $this->check();
            switch ($this->connexion->driver())
            {
                case MYSQL :
                    $data = $this->connexion->set("RENAME TABLE {$this->table} TO ?")->with(compact('new_name'))->execute();
                    assign($data, $this->table, $new_name);

                    return $data;
                break;
                case POSTGRESQL :
                case SQLITE :
                    $data = $this->connexion->set("ALTER TABLE {$this->table} RENAME TO ?")->with(compact('new_name'))->execute();
                    assign($data, $this->table, $new_name);

                    return $data;
                break;
                case SQL_SERVER :
                    $data = $this->connexion->set("EXEC sp_rename '{$this->table}', '?'")->with(compact('new_name'))->execute();
                    assign($data, $this->table, $new_name);

                    return $data;
                break;

            }
            return false;
        }


        /**
         *
         * Display all tables
         *
         * @return array
         *
         * @throws Kedavra
         */
        public function show(): array
        {
            switch ($this->connexion->driver())
            {
                case MYSQL :

                    return $this->connexion->set('SHOW TABLES')->get(\PDO::FETCH_COLUMN);

                break;
                case POSTGRESQL :
                   return  $this->connexion->set("SELECT table_name FROM information_schema.tables WHERE  table_type = 'BASE TABLE' AND table_schema NOT IN ('pg_catalog', 'information_schema');")->get(COLUMNS);
               break;
               case SQLITE :
                    return $this->connexion->set("SELECT tbl_name FROM sqlite_master")->get(COLUMNS);
                break;
                case SQL_SERVER:
                    return $this->connexion->set("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE='BASE TABLE'")->get(COLUMNS);
                break;
            }
           return  [];
        }

        /**
         *
         * Remove the table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function drop(): bool
        {
            $this->check();

            return $this->exist($this->table) ?  $this->connexion->set("DROP TABLE {$this->table}")->execute() : false;
        }

        /**
         *
         * Remove records in a table
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function truncate(): bool
        {
            $this->check();

            switch ($this->connexion->driver())
            {
                case MYSQL :
                    return $this->connexion->set("TRUNCATE TABLE {$this->table}")->execute();
                break;
                case POSTGRESQL :
                    return $this->connexion->set("TRUNCATE TABLE {$this->table} RESTART IDENTITY")->execute();
                break;
                case SQLITE :
                    return $this->connexion->set("DELETE  FROM {$this->table}",'VACUUM')->execute();
                break;
                default:
                    return false;
                break;
            }
        }

        /**
         *
         * Get all columns inside the table
         *
         * @return array
         *
         * @throws Kedavra
         *
         */
        public function columns(): array
        {

            $this->check();

            if (def($this->columns) && equal($this->table,$this->saved_table))
                return $this->columns;


            $x =  collect();
            switch ($this->connexion->driver())
            {
                case MYSQL:
                    $this->columns =  $this->connexion->set("SHOW FULL COLUMNS FROM {$this->table}")->get(COLUMNS);
                    $this->saved_table = $this->table;
                break;
                case POSTGRESQL:
                    $this->columns = $this->connexion->set("SELECT column_name FROM information_schema.columns WHERE table_name ='{$this->table}'")->get(COLUMNS);
                    $this->saved_table = $this->table;
                break;
                case SQLITE:
                    foreach ($this->connexion->set("PRAGMA table_info({$this->table})")->get(OBJECTS) as $c)
                        $x->push($c->name);
                    $this->saved_table = $this->table;
                    $this->columns =  $x->all();
                break;
                case SQL_SERVER:
                    $this->saved_table = $this->table;
                    $this->columns =  $this->connexion->set("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$this->table}'")->get(COLUMNS);
                break;
                default:
                    return  [];
                break;
            }
            return  $this->columns;
        }

        /**
         *
         *
         * Get the primary key
         *
         * @return string
         *
         * @throws Kedavra
         *
         */
        public function primary(): string
        {
            $this->check();

            if (def($this->primary))
                return $this->primary;
            switch ($this->connexion->driver())
            {
                case MYSQL:
                    $this->primary =  collect($this->connexion->set("show columns from {$this->table} where `Key` = 'PRI';")->get(COLUMNS))->first();
                break;
                case POSTGRESQL:
                    $this->primary = collect($this->connexion->set("select column_name FROM information_schema.key_column_usage WHERE table_name = '{$this->table}';")->get(COLUMNS))->first();
                break;
                case SQLITE:
                    foreach ($this->connexion->set("PRAGMA table_info({$this->table})")->get(OBJECTS) as $column)
                        if ($column->pk)
                            $this->primary = $column->name;

                break;
                case SQL_SERVER:
                  return  collect($this->connexion->set("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME  ='{$this->table}'  AND CONSTRAINT_NAME LIKE 'PK%'")->get(COLUMNS))->first();
                break;
                default:
                    return '';
                break;
            }
            return  $this->primary;
        }

        /**
         * @throws Kedavra
         */
        private function check()
        {
            is_true(not_def($this->table),true,"Select a table");
        }

        /**
         *
         * Count the tables
         *
         * @return int
         *
         * @throws Kedavra
         *
         */
        public function sum():int
        {
            return count($this->show());
        }

        /**
         * @return string|null
         */
        public function current(): ?string
        {
            return $this->table;
        }
    }
}