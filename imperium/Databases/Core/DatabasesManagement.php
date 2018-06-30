<?php
/**
 * fumseck added DatabasesManagement.php to imperium
 * The 11/09/17 at 09:36
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
 **/


namespace Imperium\Databases\Core {


    use Imperium\Databases\Eloquent\Bases\Base;
    use Imperium\Databases\Exception\IdentifierException;
    use PDO;

    interface DatabasesManagement
    {
        /**
         * show databases
         *
         * @return array
         */
        public function show(): array;

        /**
         * delete all database hosted on server not ignored
         *
         * @return bool
         * @throws IdentifierException
         */
        public function dropAll():bool;

        /**
         * create database
         *
         * @param string $database
         *
         * @return bool
         */
        public function create(string $database): bool;

        /**
         * set the encoding option
         *
         * @param string $option
         *
         * @return Base
         */
        public function setEncodingOptions(string $option): Base;

        /**
         * set database encoding
         *
         * @param string $encoding
         *
         * @return Base
         */
        public function setEncoding(string $encoding): Base;

        /**
         * set database charset
         *
         * @param string $collation
         *
         * @return Base
         *
         */
        public function setCollation(string $collation): Base;

        /**
         * set database type
         *
         * @param string $driver
         *
         * @return Base
         */
        public function setDriver(string $driver): Base;

        /**
         * set database password
         *
         * @param string $password
         *
         * @return Base
         */
        public function setPassword(string $password): Base;

        /**
         * set database user
         *
         * @param string $username
         *
         * @return Base
         */
        public function setUser(string $username): Base;

        /**
         * set database name
         *
         * @param string $name
         *
         * @return Base
         */
        public function setName(string $name): Base;

        /**
         * delete a database
         *
         * @param string $database
         *
         * @return bool
         */
        public function drop(string $database): bool;

        /**
         * restore a database
         *
         * @param string $base
         * @param string $sqlFile
         *
         * @return bool
         */
        public function restore(string $base,string $sqlFile): bool;

        /**
         * dump a database
         */
        public function dump();

        /**
         * check if a database exist
         *
         * @param string $base
         * @return bool
         */
        public function exist(string $base = ''): bool;

        /**
         * get database charset
         *
         * @return array
         */
        public function getCharset(): array;

        /**
         * get database collation
         *
         * @return array
         */
        public function getCollation(): array;

        /**
         * define hidden databases
         *
         * @param array $databases
         *
         * @return Base
         */
        public function setHidden(array $databases): Base;

        /**
         * start query builder
         *
         * @return Base
         */
        public static function manage(): Base;

        /**
         * Get a pdo instance
         *
         * @return PDO|null
         */
        public function getInstance();


        /**
         * set dump directory path
         *
         * @param string $path
         *
         * @return Base
         */
        public function setDumpDirectory(string $path) : Base;
    }
}