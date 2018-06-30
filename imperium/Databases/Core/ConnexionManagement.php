<?php
/**
 * fumseck added ConnexionBuilder.php to imperium
 * The 11/09/17 at 06:18
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


    use Imperium\Databases\Eloquent\Connexion\Connexion;

    interface ConnexionManagement
    {
        /**
         * start connection
         *
         * @return Connexion
         */
        public static function connect(): Connexion;

        /**
         * set type of database
         *
         * @param string $driver
         *
         * @return Connexion
         */
        public function setDriver(string $driver): Connexion;

        /**
         * define username
         *
         * @param string $username
         *
         * @return Connexion
         */
        public function setUser(string $username): Connexion;

        /**
         * define database name
         *
         * @param string $database
         *
         * @return Connexion
         */
        public function setDatabase(string $database): Connexion;

        /**
         * set password
         *
         * @param string $password
         *
         * @return Connexion
         */
        public function setPassword(string $password): Connexion;

        /**
         * set database encoding
         *
         * @param string $encoding
         *
         * @return Connexion
         */
        public function setEncoding(string $encoding): Connexion;

        /**
         *
         *
         * @return null|\PDO
         */
        public function getMysqlConnection();

        /**
         * @return \PDO|null
         */
        public function getPostgresqlConnection();

        /**
         * @return \PDO|null
         */
        public function getSqliteConnection();

        /**
         * get the connexion
         *
         * @return \PDO|null
         */
        public function getConnexion();
    }
}