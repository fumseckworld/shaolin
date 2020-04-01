<?php

declare(strict_types=1);

namespace Eywa\Database\Table {


    use Eywa\Collection\Collect;
    use Eywa\Console\Shell;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Exception\Kedavra;
    use PDO;

    class Table implements Records
    {
        /**
         * @var Connect
         */
        private Connect $connect;
        /**
         * @var string
         */
        private string $table = '';
        private Collect $columns ;
        private string $saved_table = '';
        private string $primary = 'id';

        /**
         * @inheritDoc
         */
        public function __construct(Connect $connect, string $table)
        {
            $this->connect = $connect;
            $this->table = $table;
            $this->columns = collect();
        }

        /**
         * @param int $pdo_mode
         * @return array
         * @throws Kedavra
         */
        public function content(int $pdo_mode = PDO::FETCH_OBJ): array
        {
            return $this->connect->set(
                sprintf(
                    'SELECT * FROM %s',
                    $this->table
                )
            )
                ->get($pdo_mode);
        }
        /**
         * @inheritDoc
         */
        public function drop(): bool
        {
            return  $this->connect->set(
                sprintf(
                    'DROP TABLE %s',
                    $this->table
                )
            )->execute();
        }

        /**
         * @inheritDoc
         */
        public function exist(): bool
        {
            return $this->show()->exist($this->table);
        }

        /**
         * @inheritDoc
         */
        public function truncate(): bool
        {
            switch ($this->connect->driver()) {
                case MYSQL:
                    return $this->connect->set(
                        sprintf(
                            'TRUNCATE TABLE %s',
                            $this->table
                        )
                    )->execute();
                case POSTGRESQL:
                    return $this->connect->set(
                        sprintf(
                            'TRUNCATE TABLE %s RESTART IDENTITY',
                            $this->table
                        )
                    )->execute();
                case SQLITE:
                    return $this->connect->set(
                        sprintf(
                            'DELETE  FROM %s',
                            $this->table
                        )
                    )->set('VACUUM')->execute();
                default:
                    return false;
            }
        }

        /**
         * @inheritDoc
         */
        public function remove(array $columns): bool
        {
            $mysql = function (string $x) {
                return sprintf('DROP COLUMN %s', $x);
            };
            switch ($this->connect->driver()) {
                case MYSQL:
                case POSTGRESQL:
                    return $this->connect->set(
                        sprintf(
                            'ALTER TABLE %s %s',
                            $this->table,
                            collect($columns)
                            ->for($mysql)
                            ->join()
                        )
                    )->execute();
                default:
                    return false;
            }
        }

        /**
         * @inheritDoc
         */
        public function rename(string $new_name): bool
        {
            switch ($this->connect->driver()) {
                case MYSQL:
                    return $this->connect->set(
                        sprintf(
                            'RENAME TABLE %s TO %s',
                            $this->table,
                            $new_name
                        )
                    )->execute();
                case POSTGRESQL:
                    return $this->connect->set(
                        sprintf(
                            'ALTER TABLE %s RENAME TO %s',
                            $this->table,
                            $new_name
                        )
                    )->execute();
                default:
                    return false;
            }
        }

        /**
         * @inheritDoc
         */
        public function has(array $columns): bool
        {
            foreach ($columns as $column) {
                if ($this->columns()->notExist($column)) {
                    return false;
                }
            }

            return true;
        }

        /**
         * @inheritDoc
         */
        public function renameColumn(string $column, string $new_name): bool
        {
            switch ($this->connect->driver()) {
                case MYSQL:
                case POSTGRESQL:
                    return $this->connect->set(
                        sprintf(
                            'ALTER TABLE %s RENAME COLUMN %s TO %s',
                            $this->table,
                            $column,
                            $new_name
                        )
                    )->execute();
                default:
                    return false;
            }
        }

        /**
         * @inheritDoc
         */
        public function primary(): string
        {
            if (def($this->primary)) {
                return $this->primary;
            }

            switch ($this->connect->driver()) {
                case MYSQL:
                    $this->primary =
                        collect($this->connect->set(
                            sprintf(
                                'show columns from %s where `Key` = \'PRI\' ',
                                $this->table
                            )
                        )
                        ->get(COLUMNS))->first();
                    break;
                case POSTGRESQL:
                    $this->primary =
                        collect($this->connect->set(
                            sprintf(
                                'select column_name FROM information_schema.key_column_usage WHERE table_name = \'%s\'',
                                $this->table
                            )
                        )->get(COLUMNS))->first();
                    break;
                case SQLITE:
                    foreach (
                        $this->connect->set(
                            sprintf(
                                'PRAGMA table_info(%s)',
                                $this->table
                            )
                        )->get(OBJECTS) as $column
                    ) {
                        if ($column->pk) {
                            $this->primary = $column->name;
                        }
                    }
                    break;
                case SQL_SERVER:
                    $this->primary =   collect($this->connect->set(
                        sprintf('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME  =\'%s\' 
                                        AND CONSTRAINT_NAME LIKE \'PK\'', $this->table)
                    )->get(COLUMNS))->first();
                    break;
                default:
                    return '';
            }
            return  $this->primary;
        }

        /**
         * @inheritDoc
         */
        public function export(): bool
        {
            $file = sprintf('%s.sql', $this->connect->base());
            switch ($this->connect->driver()) {
                case MYSQL:
                    return (new Shell(sprintf(
                        'mysqldump -u %s -p%s %s > %s',
                        $this->connect->username(),
                        $this->connect->password(),
                        $this->connect->base(),
                        $file
                    )))->run();
                case POSTGRESQL:
                    return (new Shell(sprintf(
                        'pg_dump -h %s  -U %s %s > %s',
                        $this->connect->hostname(),
                        $this->connect->username(),
                        $this->connect->base(),
                        $file
                    )))->run();
                case SQLITE:
                    return (new Shell(sprintf('sqlite3 %s  > %s', $this->connect->base(), $file)))->run();
                default:
                    return false;
            }
        }

        /**
         * @inheritDoc
         */
        public function import(): bool
        {
            $password = $this->connect->password();
            $username = $this->connect->username();
            $host = $this->connect->hostname();
            $base = $this->connect->base();
            $file = base('db', 'dump', sprintf('%s.sql', $base));


            if (!file_exists($file)) {
                return  false;
            }

            switch ($this->connect->driver()) {
                case MYSQL:
                    return (new Shell(
                        sprintf(
                            'mysqldump  -h %s -u %s -p%s %s < %s',
                            $host,
                            $username,
                            $password,
                            $base,
                            $file
                        )
                    ))->run();
                case POSTGRESQL:
                    return (new Shell(
                        sprintf(
                            'psql -h %s -U %s %s < %s',
                            $host,
                            $username,
                            $base,
                            $file
                        )
                    ))->run();
                case SQLITE:
                    return (new Shell(
                        sprintf(
                            'sqlite3  %s < %s',
                            $base,
                            $file
                        )
                    ))->run();
                default:
                    return false;
            }
        }


        /**
         * @inheritDoc
         */
        public function show(): Collect
        {
            switch ($this->connect->driver()) {
                case MYSQL:
                    return collect($this->connect->set('SHOW TABLES')->get(COLUMNS));
                case POSTGRESQL:
                    return  collect($this->connect->set(
                        'SELECT table_name FROM information_schema.tables 
                                WHERE  table_type = \'BASE TABLE\' AND table_schema 
                                NOT IN (\'pg_catalog\', \'information_schema\');'
                    )->get(COLUMNS));
                case SQLITE:
                    return collect($this->connect->set('SELECT tbl_name FROM sqlite_master')->get(COLUMNS));
                case SQL_SERVER:
                    return collect($this->connect
                            ->set('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES 
                                         WHERE TABLE_TYPE=\'BASE TABLE\'')->get(COLUMNS));
                default:
                    return collect();
            }
        }

        /**
         * @inheritDoc
         */
        public function columns(): Collect
        {
            if (def($this->columns) && equal($this->table, $this->saved_table)) {
                return $this->columns;
            }


            $x =  collect();
            switch ($this->connect->driver()) {
                case MYSQL:
                    $this->columns =  collect($this->connect->set(
                        sprintf(
                            'SHOW FULL COLUMNS FROM %s',
                            $this->table
                        )
                    )->get(COLUMNS));
                    $this->saved_table = $this->table;
                    break;
                case POSTGRESQL:
                    $this->columns = collect($this->connect->set(
                        "SELECT column_name FROM information_schema.columns WHERE table_name ='{$this->table}'"
                    )->get(COLUMNS));
                    $this->saved_table = $this->table;
                    break;
                case SQLITE:
                    foreach (
                        $this->connect->set(
                            "PRAGMA table_info({$this->table})"
                        )->get(OBJECTS) as $c
                    ) {
                        $x->push($c->name);
                    }
                    $this->saved_table = $this->table;
                    $this->columns =  $x;
                    break;
                case SQL_SERVER:
                    $this->saved_table = $this->table;
                    $this->columns =  collect($this->connect->set(
                        "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$this->table}'"
                    )->get(COLUMNS));
                    break;
                default:
                    return  collect();
            }
            return  $this->columns;
        }

        /**
         * @inheritDoc
         */
        public function types(): Collect
        {
            return collect();
        }

        /**
         * @inheritDoc
         */
        public function connexion(): Connect
        {
            return $this->connect;
        }
    }
}
