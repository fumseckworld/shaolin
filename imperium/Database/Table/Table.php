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

    use Imperium\Database\Connection\Connect;
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
     */
    class Table extends Connect
    {

        /**
         *
         * Return all columns inside a table.
         *
         * @param string $table The tables to analyse.
         *
         * @return array
         *
         */
        public function columns(string $table): array
        {
            $fields = [];
            switch ($this->driver()) {
                case MYSQL:
                    foreach ($this->get("SHOW FULL COLUMNS FROM {$table}") as $column) {
                        array_push($fields, $column->Field);
                    }
                    break;
                case POSTGRESQL:
                    foreach (
                        $this->get(
                            "SELECT column_name FROM information_schema.columns WHERE table_name ='{$table}'"
                        ) as $column
                    ) {
                        array_push($fields, $column->column_name);
                    }
                    break;
                case SQLITE:
                    foreach ($this->get("PRAGMA table_info({$table})") as $column) {
                        array_push($fields, $column->name);
                    }
                    break;
            }

            return $fields;
        }

        /**
         *
         * Get the primary key of the given table.
         *
         * @param string $table The table name.
         *
         * @throws Kedavra
         *
         * @return string
         *
         */
        public function primary(string $table): string
        {

            switch ($this->driver()) {
                case MYSQL:
                    foreach ($this->get("show columns from {$table} where `Key` = 'PRI';") as $key) {
                        return $key->Field;
                    }

                    break;
                case POSTGRESQL:
                    foreach (
                        $this->get(
                            "select column_name FROM information_schema.key_column_usage WHERE table_name = '{$table}';"
                        ) as $key
                    ) {
                        return $key->column_name;
                    }

                    break;
                case SQLITE:
                    foreach ($this->get("PRAGMA table_info({$table})") as $field) {
                        if (def($field->pk)) {
                            return $field->name;
                        }
                    }
                    break;
            }
            throw  new Kedavra('We have not found a primary key');
        }
    }
}
