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

namespace Imperium\Database\Table {

    use Imperium\Exception\Kedavra;

    /**
     *
     * Management on all tables in a database.
     *
     * This class contains all useful methods to manage an get the table content.
     *
     * @author Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Database\Table\Table
     * @version 12
     *
     * @property string $table      The table name.
     * @property string $primary    The primary key.
     * @property array  $columns    The table columns name.
     *
     */
    class Table
    {

        private ?string $primary = null;

        private array $columns = [];

        /**
         *
         * Set the table name to use.
         *
         * @param string $table The table name.
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
         * Return all columns inside a table.
         *
         * @return array
         *
         */
        public function columns(): array
        {
            $x = app('connect');

            if (empty($this->columns)) {
                $fields = [];
                switch ($x->driver()) {
                    case MYSQL:
                        foreach ($x->get("SHOW FULL COLUMNS FROM {$this->table}") as $column) {
                            array_push($fields, $column->Field);
                        }
                        break;
                    case POSTGRESQL:
                        foreach (
                            $x->get(
                                "SELECT column_name FROM information_schema.columns WHERE table_name ='{$this->table}'"
                            ) as $column
                        ) {
                            array_push($fields, $column->column_name);
                        }
                        break;
                    case SQLITE:
                        foreach ($x->get("PRAGMA table_info({$this->table})") as $column) {
                            array_push($fields, $column->name);
                        }
                        break;
                }
                $this->columns = $fields;
            }

            return $this->columns;
        }

        /**
         *
         * Get the primary key of the given table.
         *
         * @throws Kedavra
         *
         * @return string
         *
         */
        public function primary(): string
        {

            if (is_null($this->primary)) {
                $x = app('connect');
                switch ($x->driver()) {
                    case MYSQL:
                        foreach ($x->get("show columns from {$this->table} where `Key` = 'PRI';") as $key) {
                            $this->primary =  $key->Field;
                        }

                        break;
                    case POSTGRESQL:
                        foreach (
                            $x->get(
                                "select column_name FROM information_schema.key_column_usage 
                                WHERE table_name = '{$this->table}';"
                            ) as $key
                        ) {
                            $this->primary =  $key->column_name;
                        }

                        break;
                    case SQLITE:
                        foreach ($x->get("PRAGMA table_info({$this->table})") as $field) {
                            if (def($field->pk)) {
                                $this->primary =  $field->name;
                            }
                        }
                        break;
                }
                if (is_null($this->primary)) {
                    throw  new Kedavra('We have not found a primary key');
                }
            }
            return $this->primary;
        }
    }
}
