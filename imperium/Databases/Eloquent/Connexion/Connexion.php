<?php
/**
 * fumseck added Connexion.php to imperium
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

namespace Imperium\Databases\Eloquent\Connexion {


    use Imperium\Databases\Core\ConnexionManagement;
    use PDO;
    use PDOException;

    class Connexion implements ConnexionManagement
    {
        const MYSQL = 'mysql';

        const POSTGRESQL = 'pgsql';

        const SQLITE = 'sqlite';

        const ORACLE = 'oci';

        /**
         * @var string $type
         */
        private $driver;

        /**
         * @var string $username
         */
        private $username;

        /**
         * @var string $database
         */
        private $database;

        /**
         * @var string $password
         */
        private $password;

        /**
         * @var string $encoding
         */
        private $encoding;

        /**
         * start connection
         *
         * @return Connexion
         */
        public static function connect(): Connexion
        {
            return new static();
        }

        /**
         * set type of database
         *
         * @param string $driver
         *
         * @return Connexion
         *
         */
        public function setDriver(string $driver): Connexion
        {
            $this->driver = $driver;

            return $this;
        }

        /**
         * define username
         *
         * @param string $username
         *
         * @return Connexion
         */
        public function setUser(string $username): Connexion
        {
            $this->username = $username;

            return $this;
        }

        /**
         * define database name
         *
         * @param string $database
         *
         * @return Connexion
         */
        public function setDatabase(string $database): Connexion
        {
            $this->database = $database;

            return $this;
        }

        /**
         * set password
         *
         * @param string $password
         *
         * @return Connexion
         */
        public function setPassword(string $password): Connexion
        {
            $this->password = $password;

            return $this;
        }

        /**
         * set database encoding
         *
         * @param string $encoding
         *
         * @return Connexion
         */
        public function setEncoding(string $encoding): Connexion
        {
            $this->encoding = $encoding;

            return $this;
        }

        /**
         * get the connexion
         *
         * @return \PDO|null
         */
        public function getConnexion()
        {
            switch ($this->driver)
            {
                case Connexion::MYSQL:
                    return $this->getMysqlConnection();
                break;
                case Connexion::POSTGRESQL:
                   return $this->getPostgresqlConnection();
                break;
                case Connexion::SQLITE:
                    return $this->getSqliteConnection();
                break;
                case Connexion::ORACLE:
                    return $this->getOracleConnection();
                break;
                default:
                    return null;
                break;
            }
        }

        /**
         *
         * @return null|PDO
         */
        public function getMysqlConnection()
        {
            if (empty($this->database))
            {
                try{
                    return new PDO("mysql:host=localhost;",$this->username,$this->password);
                }catch (PDOException $e)
                {
                    return null;
                }
            }

            try{
                return new PDO("mysql:host=localhost;dbname={$this->database}",$this->username,$this->password);
            }catch (PDOException $e)
            {
                return null;
            }
        }

        /**
         * @return \PDO|null
         */
        public function getPostgresqlConnection()
        {
            if(empty($this->database))
            {
                try{
                    return new PDO("pgsql:host=localhost;",$this->username,$this->password);
                }catch (PDOException $e)
                {
                    return null;
                }
            } else
            {

                try{
                    return new PDO("pgsql:host=localhost;dbname={$this->database}",$this->username,$this->password);
                }catch (PDOException $e)
                {
                    return null;
                }
            }
        }

        /**
         * @return \PDO|null
         */
        public function getSqliteConnection()
        {
            if (empty($this->database))
            {
                try {
                    return new PDO('sqlite::memory:');
                }catch (PDOException $e)
                {
                    return null;
                }

            }

            try{
                return new PDO("sqlite:{$this->database}",null,null);
            }catch (PDOException $e)
            {
                return null;
            }
        }

        public function getOracleConnection()
        {
            try{
                return new PDO("oci:dbname{$this->database}",$this->username,$this->password);
            }catch (PDOException $e)
            {
                return null;
            }
        }
    }
}