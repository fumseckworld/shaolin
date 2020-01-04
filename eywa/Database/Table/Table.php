<?php
declare(strict_types=1);

namespace Eywa\Database\Table {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Exception\Kedavra;

    class Table
    {
        /**
         *
         * The table name
         *
         */
        private string $table;

        /**
         *
         * The connexion
         *
         */
        private Connect $connexion;


        /**
         * Table constructor.
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function __construct()
        {

            $this->connexion = ioc(Connect::class)->get();
        }

        public function from(string $table):Table
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
            $seed = collect();

            for ($i = 0; $i != $records; $i++)
            {
                $x = call_user_func_array($callable, [faker(config('lang', 'locale')), $this]);

                is_false(is_string($x),true,"The return value must be a string");

                $seed->push($x);

            }
           return $this->connexion->set("INSERT INTO {$this->table} ({$this->columns()->join()}) VALUES  {$seed->join()}")->execute();

        }

        /**
         *
         * Check if the table exist
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function exist(): bool
        {
            return $this->show()->exist($this->table);
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
            return  $this->connexion->secure($x);
        }
        /**
         *
         * Check if a column exist
         *
         * @param string[] $columns
         * @return bool
         *
         * @throws Kedavra
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
         *
         */
        public function rename(string $new_name): bool
        {

            switch ($this->connexion->driver())
            {
                case MYSQL :
                    $data = $this->connexion->set("RENAME TABLE {$this->table} TO ?")->execute(compact('new_name'));
                    assign($data, $this->table, $new_name);

                    return $data;
                break;
                case POSTGRESQL :
                case SQLITE :
                    $data = $this->connexion->set("ALTER TABLE {$this->table} RENAME TO ?")->execute(compact('new_name'));
                    assign($data, $this->table, $new_name);

                    return $data;
                break;
                case SQL_SERVER :
                    $data = $this->connexion->set("EXEC sp_rename '{$this->table}', '?'")->execute(compact('new_name'));
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
         *
         */
        public function show(): array
        {

            switch ($this->connexion->driver())
            {
                case MYSQL :
                    return  $this->connexion->set('SHOW TABLES')->get();
                break;
                case POSTGRESQL :
                   return  $this->connexion->set("SELECT table_name FROM information_schema.tables WHERE  table_type = 'BASE TABLE' AND table_schema NOT IN ('pg_catalog', 'information_schema');")->get();
               break;
               case SQLITE :
                    return $this->connexion->set("SELECT tbl_name FROM sqlite_master")->get();
                break;
                case SQL_SERVER:
                    return $this->connexion->set("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE='BASE TABLE'")->get();
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
         */
        public function drop(): bool
        {
            return $this->connexion->set("DROP TABLE {$this->table}")->execute();
        }

        /**
         *
         * Remove records in a table
         *
         * @return bool
         *
         *
         */
        public function truncate(): bool
        {
            switch ($this->connexion->driver()) {
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
         *
         */
        public function columns(): array
        {
            $fields = collect();

            switch ($this->connexion->driver())
            {
                case MYSQL:
                    foreach ($this->connexion->set("SHOW FULL COLUMNS FROM {$this->table}")->get() as $column)
                        $fields->push($column->Field);
                    break;
                case POSTGRESQL:
                    foreach ($this->connexion->set("SELECT column_name FROM information_schema.columns WHERE table_name ='{$this->table}'")->get() as $column)
                        $fields->push($column->column_name);
                    break;
                case SQLITE:
                    foreach ($this->connexion->set("PRAGMA table_info({$this->table})")->get() as $column)
                        $fields->push($column->name);
                    break;
                case SQL_SERVER:
                    break;

            }

            return $fields->all();
        }

        /**
         *
         *
         * Get the primary key
         *
         * @return string
         *
         *
         */
        public function primary(): string
        {
            switch ($this->connexion->driver())
            {
                case MYSQL:
                    foreach ($this->connexion->set("show columns from {$this->table} where `Key` = 'PRI';")->get() as $key)
                        return $key->Field;
                break;
                case POSTGRESQL:
                    foreach ($this->connexion->set("select column_name FROM information_schema.key_column_usage WHERE table_name = '{$this->table}';")->get() as $key)
                        return $key->column_name;
                break;
                case SQLITE:
                    foreach ($this->connexion->set("PRAGMA table_info({$this->table})")->get() as $field)
                    {
                        if (def($field->pk))
                            return $field->name;
                    }
                break;
                case SQL_SERVER:
                break;
                default:
                    return '';
                break;
            }
            return '';
        }


    }
}