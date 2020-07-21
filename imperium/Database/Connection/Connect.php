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

declare(strict_types=1);

namespace Imperium\Database\Connection {
    
    use DI\DependencyException;
    use DI\NotFoundException;
    use PDO;
    use PDOException;
    
    /**
     *
     * Represent a connection between php code and a database.
     *
     * Groups off all method useful to execute queries.
     *
     * @author  Willy Micieli <fumseck@fumseck.org>
     * @package Imperium\Database\Connection\Connect
     * @version 12
     *
     * @property string $driver    The current pdo driver value
     * @property string $base      The current pdo database value
     * @property string $username  The current pdo username value
     * @property string $password  The current pdo password value
     * @property string $host      The current pdo hostname value
     * @property PDO    $pdo       The pdo instance
     */
    class Connect
    {
        
        /**
         *
         * Build a new instance
         *
         * Initialize required values
         *
         *
         * @param string $driver   The driver to use
         * @param string $base     The database name
         * @param string $username The database username
         * @param string $password The database password
         * @param string $host     The database hostname
         */
        final public function __construct(
            string $driver = '',
            string $base = '',
            string $username = '',
            string $password = '',
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
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Connect
         *
         */
        private function dev(): Connect
        {
            return new static(
                env('DEVELOP_DB_DRIVER', 'mysql'),
                env('DEVELOP_DB_NAME', 'ikran'),
                env('DEVELOP_DB_USERNAME', 'ikran'),
                env('DEVELOP_DB_PASSWORD', ''),
                env('DEVELOP_DB_HOST', 'localhost')
            );
        }
        
        /**
         *
         * Call the constructor with the production environment information.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Connect
         *
         */
        private function prod(): Connect
        {
            return new static(
                env('DB_DRIVER', 'mysql'),
                env('DB_NAME', 'eywa'),
                env('DB_USERNAME', 'eywa'),
                env('DB_PASSWORD', ''),
                env('DB_HOST', 'localhost')
            );
        }
        
        /**
         *
         * Call the constructor with the test environment information.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Connect
         *
         */
        private function test(): Connect
        {
            return new static(
                env('TESTS_DB_DRIVER', 'mysql'),
                env('TESTS_DB_NAME', 'vortex'),
                env('TESTS_DB_USERNAME', 'vortex'),
                env('TESTS_DB_PASSWORD', ''),
                env('TESTS_DB_HOST', 'localhost')
            );
        }
        
        /**
         *
         * Execute a query sql.
         *
         * Return true on success of false on failure.
         *
         * @param string $sql  The query to execute
         * @param array  $args The query args
         *
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return boolean
         *
         */
        public function exec(string $sql, array $args = []): bool
        {
            $stm = $this->pdo()->prepare($sql);
            
            $success = $stm->execute($args);
            
            $stm->closeCursor();
            
            $stm = null;
            
            return $success;
        }
        
        /**
         *
         * Execute the query and return the results in an array.
         *
         * @param string $sql         The query to execute
         * @param array  $args        The query arguments
         * @param int    $output_mode The output style
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return array
         *
         */
        public function get(string $sql, array $args = [], int $output_mode = PDO::FETCH_OBJ): array
        {
            $stm = $this->pdo()->prepare($sql);
            try {
                $stm->execute($args);
            } catch (PDOException $e) {
                $stm->closeCursor();
                $stm = null;
                return [];
            }
            $data = $stm->fetchAll($output_mode);
            $stm->closeCursor();
            $stm = null;
            
            return is_bool($data) ? [] : $data;
        }
        
        /**
         *
         * Get the correct environment.
         *
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return Connect
         *
         */
        private function env(): Connect
        {
            $env = config('mode', 'connection');
            if (strcmp($env, 'prod') === 0) {
                return $this->prod();
            } elseif (strcmp($env, 'dev') === 0) {
                return $this->dev();
            }
            return $this->test();
        }
        
        /**
         *
         * Get the pdo driver used.
         *
         * @return string
         *
         */
        public function driver(): string
        {
            return $this->driver;
        }
        
        
        /**
         *
         * Return the current username used.
         *
         * @return string
         *
         */
        public function username(): string
        {
            return $this->username;
        }
        
        /**
         *
         * Return the current password used.
         *
         * @return string
         *
         */
        public function password(): string
        {
            return $this->password;
        }
        
        /**
         *
         * Return the current base used.
         *
         * @return string
         *
         */
        public function base(): string
        {
            return $this->base;
        }
        
        /**
         *
         * Return the current hostname used.
         *
         * @return string
         *
         */
        public function hostname(): string
        {
            return $this->host;
        }
        
        /**
         *
         * Check if the current driver used is mysql.
         *
         * Return true if mysql is used or return false.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return boolean
         *
         */
        public function mysql(): bool
        {
            return strcmp($this->env()->driver(), MYSQL) == 0;
        }
        
        /**
         *
         * Check if the current driver used is postgresql.
         *
         * Return true if postgresql is used or return false.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return boolean
         *
         */
        public function postgresql(): bool
        {
            return strcmp($this->env()->driver(), POSTGRESQL) == 0;
        }
        
        
        /**
         *
         * Check if the current driver used is sqlite.
         *
         * Return true if sqlite is used or return false.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return boolean
         *
         */
        public function sqlite(): bool
        {
            return strcmp($this->env()->driver(), SQLITE) == 0;
        }
        
        /**
         *
         * Build the pdo instance.
         *
         * @throws DependencyException
         * @throws NotFoundException
         *
         * @return PDO
         *
         */
        protected function pdo(): PDO
        {
            if (!$this->pdo instanceof PDO) {
                if ($this->env()->mysql()) {
                    $this->pdo =
                        new PDO(
                            sprintf(
                                'mysql:host=%s;port=3306;dbname=%s;charset=UTF8',
                                $this->env()->hostname(),
                                $this->env()->base()
                            ),
                            $this->env()->username(),
                            $this->env()->password()
                        );
                } elseif ($this->env()->postgresql()) {
                    $this->pdo = new PDO(
                        sprintf(
                            'pgsql:host=%s;port=5432;dbname=%s;options=\'--client_encoding=UTF8\'',
                            $this->env()->hostname(),
                            $this->env()->base()
                        ),
                        $this->env()->username(),
                        $this->env()->password()
                    );
                } else {
                    $this->pdo = new PDO(sprintf('sqlite:%s', $this->env()->base()), '', '');
                }
                if (env('DEBUG', false)) {
                    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } else {
                    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                }
            }
            return $this->pdo;
        }
    }
}
