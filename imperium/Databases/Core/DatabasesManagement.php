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

    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\Databases\Eloquent\Bases\Base;
    use Imperium\Databases\Exception\IdentifierException;
    use PDO;

    interface DatabasesManagement
    {
        /**
         * show databases
         *
         * @return array
         *
         * @throws Exception
         */
        public function show(): array;

        /**
         * delete all database hosted on server not ignored
         *
         * @return bool
         *
         * @throws IdentifierException
         */
        public function drop_all_databases():bool;

        /**
         * Database constructor.
         *
         * @param Connect $connect
         */
        public function __construct(Connect $connect);

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
        public function set_encoding_option(string $option): Base;

        /**
         * define the fetch mode
         *
         * @param int $mode
         *
         * @return Base
         */
        public function set_fetch_mode(int $mode = PDO::FETCH_OBJ): Base;

        /**
         * set database charset
         *
         * @param string $charset
         *
         * @return Base
         */
        public function set_charset(string $charset): Base;

        /**
         * set database charset
         *
         * @param string $collation
         *
         * @return Base
         *
         */
        public function set_collation(string $collation): Base;

        /**
         * set database name
         *
         * @param string $name
         *
         * @return Base
         */
        public function set_name(string $name): Base;

        /**
         * delete a database
         *
         * @param string $database
         *
         * @return  bool
         *
         * @throws \Exception
         */
        public function drop(string $database): bool;

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
        public function charsets(): array;

        /**
         * get database collation
         *
         * @return array
         */
        public function collations(): array;

        /**
         * define hidden databases
         *
         * @param array $databases
         *
         * @return Base
         */
        public function hidden(array $databases): Base;

    }
}