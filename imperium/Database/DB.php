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

namespace Imperium\Database {

    use PDO;
    use Imperium\Exception\Kedavra;

    /**
     *
     * Represent a connection between php code and a database.
     *
     * Groups off all method useful to execute queries.
     *
     * @author Willy Micieli <fumseckworld@fumseck.eu>
     * @package Imperium\Database\DB
     * @version 12
     *
     * @property string  $driver The current pdo driver value
     * @property string  $base The current pdo database value
     * @property string  $username The current pdo username value
     * @property string  $password The current pdo password value
     * @property string  $host The current pdo hostname value
     *
     */
    class DB
    {

        /**
         *
         * Build a new instance
         *
         * Initialize required values
         *
         *
         * @param string $driver        The driver to use
         * @param string $base          The database name
         * @param string $username      The database username
         * @param string $password      The database password
         * @param string $host          The database hostname
         */
        final private function __construct(
            string $driver,
            string $base,
            string $username,
            string $password,
            string $host = 'localhost'
        ) {
            $this->driver = $driver;
            $this->base = $base;
            $this->username = $username;
            $this->password = $password;
            $this->host = $host;
        }

        /**
         *
         * Call the constructor with the development environment information.
         *
         * @return DB
         *
         */
        public static function dev(): DB
        {
            return new static('', '', '', '');
        }

        /**
         *
         * Call the constructor with the production environment information.
         *
         * @return DB
         *
         */
        public static function prod(): DB
        {
            return new static('', '', '', '');
        }

        /**
         *
         * Call the constructor with the test environment information.
         *
         * @return DB
         *
         */
        public static function test(): DB
        {
            return new static('', '', '', '');
        }

        /**
         *
         * Execute a query sql.
         *
         * Return true on success of false on failure.
         *
         * @param string $sql   The query to execute
         * @param array  $args  The query args
         *
         * @throws Kedavra
         *
         * @return boolean
         *
         */
        public function exec(string $sql, array $args = []): bool
        {
            return true;
        }

        /**
         *
         * Execute the query and return the results in an array.
         *
         * @param string  $sql          The query to execute
         * @param array   $args         The query arguments
         * @param int     $output_mode  The output style
         *
         * @throws Kedavra
         *
         * @return array
         *
         */
        public function get(string $sql, array $args = [], int $output_mode = PDO::FETCH_OBJ): array
        {
            return [];
        }

        /**
         *
         * Check if the current driver used is mysql.
         *
         * Return true if mysql is used or return false.
         *
         * @return boolean
         *
         */
        public function mysql(): bool
        {
            return strcmp($this->driver, 'mysql') == 0;
        }

        /**
         *
         * Check if the current driver used is postgresql.
         *
         * Return true if postgresql is used or return false.
         *
         * @return boolean
         *
         */
        public function postgresql(): bool
        {
            return strcmp($this->driver, 'pgsql') == 0;
        }


        /**
         *
         * Check if the current driver used is sqlite.
         *
         * Return true if sqlite is used or return false.
         *
         * @return boolean
         *
         */
        public function sqlite(): bool
        {
            return strcmp($this->driver, 'sqlite') == 0;
        }
    }
}
