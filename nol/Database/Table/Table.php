<?php

/**
 * Copyright (C) <2020>  <Willy Micieli>
 *
 * This program is free software : you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https: //www.gnu.org/licenses/>.
 *
 */

namespace Nol\Database\Table {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Nol\Database\Connection\Connect;
    use Nol\Exception\Kedavra;
    use PDO;
    use stdClass;

    /**
     *
     * Management on all tables in a database.
     *
     * This class contains all useful methods to manage an get the table content.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Database\Table\Table
     * @version 12
     *
     * @property string  $table      The table name.
     * @property string  $primary    The primary key.
     * @property array   $columns    The table columns name.
     * @property Connect $connect    The selected base.
     * @property array   $show       All tables found
     */
    class Table
    {
        /**
         *
         * @param Connect $connect The connection of the selected base.
         *
         */
        public function __construct(Connect $connect)
        {
            $this->connect = $connect;
            $this->primary = '';
            $this->columns = [];
        }

        /**
         *
         * Set the table name to use.
         *
         * @param string $table The table name.
         *
         * @return Table
         *
         */
        final public function from(string $table): Table
        {
            $this->table = $table;
            return $this;
        }

        /**
         *
         * Check if a base has tables.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return bool
         *
         */
        final public function has(): bool
        {
            return def($this->show());
        }

        /**
         *
         * Return all columns inside a table.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return array
         *
         */
        final public function columns(): array
        {
            $x = $this->connect;
            if (not_def($this->columns)) {
                switch ($x->driver()) {
                    case MYSQL:
                        $this->columns = $x->get(sprintf('SHOW FULL COLUMNS %s', $this->table), [], PDO::FETCH_COLUMN);
                        break;
                    case POSTGRESQL:
                        $this->columns = $x->get(
                            sprintf(
                                "SELECT column_name FROM information_schema.columns WHERE table_name ='%s'",
                                $this->table
                            ),
                            [],
                            PDO::FETCH_COLUMN
                        );
                        break;
                    case SQLITE:
                        $this->columns = $x->get("PRAGMA table_info({$this->table})", [], PDO::FETCH_COLUMN);
                        break;
                }
            }
            return $this->columns;
        }

        /**
         *
         * Check if the table exist.
         *
         * @param string $table The table to check existence.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return bool
         *
         */
        final public function exist(string $table): bool
        {
            return collect($this->show())->exist($table);
        }

        /**
         * @param string $table
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return bool
         *
         */
        final public function notExist(string $table): bool
        {
            return !$this->exist($table);
        }

        /**
         *
         * Rename a table.
         *
         * @param string $new The new table name.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return bool
         *
         */
        final public function rename(string $new): bool
        {
            switch ($this->connect->driver) {
                case MYSQL:
                    $data = $this->connect->exec(sprintf('RENAME TABLE %s TO %s', $this->table, $new));
                    if ($data) {
                        $this->table = $new;
                    }
                    return $data;
                case POSTGRESQL:
                case SQLITE:
                    $data = $this->connect->exec(sprintf(
                        'ALTER TABLE %s RENAME TO %s',
                        $this->table,
                        $new
                    ));

                    if ($data) {
                        $this->table = $new;
                    }
                    return $data;
                default:
                    return false;
            }
        }

        /**
         *
         * Check if a table is empty
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return bool
         *
         */
        final public function empty(): bool
        {
            return $this->sum() == 0;
        }

        /**
         *
         * Drop the table.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return boolean
         *
         */
        final public function drop(): bool
        {
            return $this->connect->exec(
                sprintf(
                    'DROP TABLE %s',
                    $this->table
                )
            );
        }

        /**
         *
         * Show all tables in a base.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return array
         *
         */
        final public function show(): array
        {
            if (empty($this->show)) {
                switch ($this->connect->driver) {
                    case MYSQL:
                        $this->show = array_unique($this->connect->get('SHOW TABLES', [], PDO::FETCH_COLUMN));
                        break;
                    case POSTGRESQL:
                        $this->show = array_unique($this->connect->get(
                            "SELECT table_name FROM information_schema.tables WHERE  table_type = 'BASE TABLE'
                            AND table_schema NOT IN ('pg_catalog', 'information_schema');",
                            [],
                            PDO::FETCH_COLUMN
                        ));
                        break;
                    case SQLITE:
                        $this->show = array_unique(
                            $this->connect->get(
                                'SELECT tbl_name FROM sqlite_master',
                                [],
                                PDO::FETCH_COLUMN
                            )
                        );
                        break;
                    default:
                        $this->show = [];
                        break;
                }
            }
            return $this->show;
        }

        /**
         *
         * Truncate a table.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return bool
         *
         */
        final public function truncate(): bool
        {
            switch ($this->connect->driver) {
                case MYSQL:
                    return $this->connect->exec(sprintf(
                        'TRUNCATE TABLE %s',
                        $this->table
                    ));
                case POSTGRESQL:
                    return $this->connect->exec(
                        sprintf(
                            'TRUNCATE TABLE %s RESTART IDENTITY',
                            $this->table
                        )
                    );
                case SQLITE:
                    return
                        $this->connect->exec(sprintf('DELETE  FROM %s', $this->table)) &&
                        $this->connect->exec('VACUUM');
                default:
                    return false;
            }
        }

        /**
         *
         * Get the primary key of the given table.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return string
         *
         */
        final public function primary(): string
        {
            if (not_def($this->primary)) {
                $x = $this->connect;
                switch ($x->driver()) {
                    case MYSQL:
                        foreach ($x->get(sprintf("show columns from %s where `Key` = 'PRI';", $this->table)) as $key) {
                            $this->primary = $key->Field;
                        }
                        break;
                    case POSTGRESQL:
                        foreach (
                            $x->get(
                                sprintf(
                                    "select column_name FROM information_schema.key_column_usage 
                                    WHERE table_name = '%s';",
                                    $this->table
                                )
                            ) as $key
                        ) {
                            $this->primary = $key->column_name;
                        }

                        break;
                    case SQLITE:
                        foreach ($x->get(sprintf('PRAGMA table_info(%s)', $this->table)) as $field) {
                            if ($field->pk) {
                                $this->primary = $field->name;
                            }
                        }
                        break;
                }
                if (not_def($this->primary)) {
                    throw  new Kedavra('We have not found a primary key');
                }
            }
            return $this->primary;
        }

        /**
         *
         * Get the contents in the table.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return array<stdClass>
         *
         */
        final public function content(): array
        {
            return $this->connect->get(sprintf('SELECT * FROM %s', $this->table));
        }


        /**
         *
         * Get the contents in the table.
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         * @return int
         *
         */
        final public function sum(): int
        {
            return intval(
                $this->connect->get(
                    sprintf(
                        'SELECT COUNT(%s) FROM %s',
                        $this->primary(),
                        $this->table
                    ),
                    [],
                    PDO::FETCH_COLUMN
                )
            );
        }
    }
}
