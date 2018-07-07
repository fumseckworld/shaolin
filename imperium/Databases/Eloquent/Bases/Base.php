<?php
/**
 * fumseck added Base.php to imperium
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


namespace Imperium\Databases\Eloquent\Bases {

    use Imperium\Databases\Core\DatabasesManagement;
    use Imperium\Databases\Dumper\Databases\MySQLDatabase;
    use Imperium\Databases\Dumper\Databases\PostgreSQLDatabase;
    use Imperium\Databases\Dumper\Databases\SQLiteDatabase;
    use Imperium\Databases\Eloquent\Connexion\Connexion;
    use Imperium\Databases\Eloquent\Share;
    use Imperium\Databases\Exception\IdentifierException;
    use Imperium\File\File;
    use PDO;

    class Base implements DatabasesManagement
    {
        use Share;

        /**
         * @var string
         */
        private $options;

        /**
         * show databases
         *
         * @throws IdentifierException
         * @return array
         */
        public function show(): array
        {
            $databases = array();

            if (is_null($this->getInstance()))
                throw IdentifierException::incorrectIdentifiers();

            switch ($this->driver)
            {
                case Connexion::MYSQL:
                    $query = $this->getInstance()->prepare('SHOW DATABASES');
                    $query->execute();
                break;
                case Connexion::POSTGRESQL:
                    $query = $this->getInstance()->prepare('select datname from pg_database');
                    $query->execute();
                break;
                case Connexion::ORACLE:
                    $query = $this->getInstance()->prepare('SELECT NAME FROM V$DATABASE;');
                    $query->execute();
                break;
                default:
                    return $databases;
                break;
            }

            foreach ($query->fetchAll() as $database)
            {
                if (!empty($this->hidden))
                {
                    if (!has(current($database), $this->hidden))
                    {
                        push($databases, current($database));
                    }
                } else {
                    push($databases, current($database));
                }
            }
            return $databases;
        }

        /**
         * create database
         * @param string $database
         * @throws IdentifierException
         *
         * @return bool
         */
        public function create(string $database): bool
        {
            if (is_null($this->getInstance()))
                throw IdentifierException::incorrectIdentifiers();

            switch ($this->driver)
            {
                case Connexion::MYSQL:
                    if (empty($this->collation) && empty($this->encoding))
                        return execute($this->getInstance()," CREATE DATABASE IF NOT EXISTS $database");

                    return execute($this->getInstance()," CREATE DATABASE IF NOT EXISTS $database DEFAULT CHARACTER SET {$this->encoding} DEFAULT COLLATE {$this->collation};");
                break;
                case Connexion::POSTGRESQL:
                    if(empty($this->collation) && empty($this->encoding))
                        return execute($this->getInstance(),"CREATE DATABASE $database TEMPLATE template0");
                    return execute($this->getInstance(),"CREATE DATABASE  $database ENCODING '{$this->encoding}' LC_COLLATE='{$this->collation}' LC_CTYPE='{$this->collation}' TEMPLATE template0; ");
                break;
                case Connexion::ORACLE:
                    if(empty($this->options) && empty($this->encoding))
                        return execute($this->getInstance()," CREATE DATABASE IF NOT EXISTS $database");
                    return execute($this->getInstance(),"CREATE DATABASE $database {$this->options} '{$this->encoding}';");
                break;
                case Connexion::SQLITE:
                    new PDO("sqlite:$database",null,null);
                    return chmod($database,0777);
                break;
                default:
                    return false;
                break;
            }
        }

        /**
         * set database encoding
         *
         * @param string $encoding
         *
         * @return Base
         */
        public function setEncoding(string $encoding): Base
        {
            $this->encoding = $encoding;

            return $this;
        }

        /**
         * set database charset
         *
         * @param string $collation
         *
         * @return Base
         *
         */
        public function setCollation(string $collation): Base
        {
            $this->collation = $collation;

            return $this;
        }

        /**
         * set database type
         *
         * @param string $driver
         *
         * @return Base
         */
        public function setDriver(string $driver): Base
        {
            $this->driver = $driver;

            return $this;
        }

        /**
         * set database password
         *
         * @param string $password
         *
         * @return Base
         */
        public function setPassword(string $password): Base
        {
            $this->password = $password;

            return $this;
        }

        /**
         * set database user
         *
         * @param string $username
         *
         * @return Base
         */
        public function setUser(string $username): Base
        {
            $this->username = $username;

            return $this;
        }

        /**
         * set database name
         *
         * @param string $name
         *
         * @return Base
         */
        public function setName(string $name): Base
        {
            $this->database = $name;

            return $this;
        }

        /**
         * delete a database
         *
         * @param string $database
         *
         * @return bool
         * @throws IdentifierException
         */
        public function drop(string $database): bool
        {
            if (is_null($this->getInstance()))
                throw IdentifierException::incorrectIdentifiers();

            switch ($this->driver)
            {
                case Connexion::SQLITE:
                    return File::delete($database);
                break;
                default:
                    return execute($this->getInstance(),"DROP DATABASE $database;");
                break;
            }
        }

        /**
         * dump a database
         */
        public function dump()
        {
            $filename = "{$this->path}/{$this->database}.sql";
            switch ($this->driver)
            {
                case Connexion::MYSQL:

                    MySQLDatabase::dump()
                        ->setDbName($this->database)
                        ->setPassword($this->password)
                        ->setUserName($this->username)
                        ->dumpToFile($filename, $this->path);

                    File::download($filename);

                break;
                case Connexion::POSTGRESQL:

                    PostgreSQLDatabase::dump()
                        ->setDbName($this->database)
                        ->setUserName($this->username)
                        ->setPassword($this->password)
                        ->dumpToFile($filename, $this->path);

                    File::download($filename);
                break;
                case Connexion::SQLITE:
                    SQLiteDatabase::dump()->setDbName($this->database)->dumpToFile($filename,$this->path);

                    File::download($filename);
                break;
            }
        }

       /**
        * check if a database exist
        *
        * @param string $base
        * @throws IdentifierException
        *
        * @return bool
        */
        public function exist(string $base = ''): bool
        {
            if (!empty($base))
                return has($base,$this->show());
            else
                return has($this->database,$this->show());

        }

        /**
         * get database charset
         *
         * @return array
         * @throws IdentifierException
         */
        public function getCharset(): array
        {
            $charset = array();

            if (is_null($this->getInstance()))
                throw IdentifierException::incorrectIdentifiers();

            switch ($this->driver)
            {
                case Connexion::MYSQL:
                    $query = $this->getInstance()->prepare('SHOW CHARACTER SET');
                    $query->execute();
                break;
                case Connexion::POSTGRESQL:
                    $query = $this->getInstance()->prepare("SELECT DISTINCT pg_encoding_to_char(conforencoding) FROM pg_conversion ORDER BY 1");
                    $query->execute();
                break;
                case Connexion::ORACLE:
                    $query = $this->getInstance()->prepare("select * from database_properties");
                    $query->execute();
                break;
                default:
                    return $charset;
                break;
            }

            foreach ($query->fetchAll() as $char)
            {
                push($charset, current($char));
            }
            return $charset;
        }

        /**
         * get database collation
         *
         * @return array
         * @throws IdentifierException
         */
        public function getCollation(): array
        {
            if (is_null($this->getInstance()))
                throw IdentifierException::incorrectIdentifiers();

            $collation = array();
            switch ($this->driver)
            {
                case Connexion::MYSQL:
                    $query = $this->getInstance()->prepare('SHOW COLLATION');
                    $query->execute();
                break;
                case Connexion::POSTGRESQL:
                    $query = $this->getInstance()->prepare("SELECT collname FROM pg_collation");
                    $query->execute();
                break;
                default:
                    return $collation;
                break;
            }

            foreach ($query->fetchAll() as $char)
            {
                push($collation, current($char));
            }
            return $collation;
        }

        /**
         * define hidden databases
         *
         * @param array $databases
         *
         * @return Base
         */
        public function setHidden(array $databases): Base
        {
            $this->hidden = $databases;

            return $this;
        }

        /**
         * start query builder
         *
         * @return Base
         */
        public static function manage(): Base
        {
            return new static();
        }

        /**
         * Get a pdo instance
         *
         * @return PDO|null
         */
        public function getInstance()
        {
            return connect($this->driver,$this->database,$this->username,$this->password);
        }


        /**
         * set dump directory path
         *
         * @param string $path
         *
         * @return Base
         */
        public function setDumpDirectory(string $path): Base
        {
            $this->path = $path;

            return $this;
        }

        /**
         * set the encoding option
         *
         * @param string $option
         *
         * @return Base
         */
        public function setEncodingOptions(string $option): Base
        {
            $this->options = $option;

            return $this;
        }

        /**
         * restore a database
         *
         * @param string $base
         * @param string $sqlFile
         *
         * @return bool
         */
        public function restore(string $base,string $sqlFile): bool
        {
            if (File::exist($sqlFile))
            {
                switch ($this->driver)
                {
                    case Connexion::MYSQL:
                        return system("mysql -uroot $base < $sqlFile");
                    break;
                    case Connexion::POSTGRESQL:
                        return system("psql -U postgres $base < $sqlFile");
                    break;
					case Connexion::SQLITE:
						return system("sqlite3 $base < $sqlFile");
					break;
            		default:
                        return false;
                    break;
                }
            }
            return false;
        }

        /**
         * delete all database hosted on server not ignored
         *
         * @return bool
         * @throws IdentifierException
         */
        public function dropAll(): bool
        {
           foreach ($this->show() as $base)
               if (!$this->drop($base))
                   return false;

           return true;
        }
    }
}
