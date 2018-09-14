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

    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\Databases\Core\DatabasesManagement;
    use Imperium\Databases\Eloquent\Share;
    use Imperium\File\File;
    use PDO;

    class Base implements DatabasesManagement
    {
        use Share;

        private $options;

        /**
         * the collation
         *
         * @var string
         */
        private $collation;

        /**
         * @var string
         */
        private $charset;

        /**
         * database name
         *
         * @var string
         */
        private $database;

        /**
         * pdo fetch mode
         *
         * @var int
         */
        private $fetch;

        /**
         * show databases
         *
         * @return array
         *
         * @throws Exception
         */
        public function show(): array
        {
            $databases = collection();

            if (def($this->hidden))
                $hidden = collection($this->hidden);
            else
                $hidden = collection();

            switch ($this->connexion->get_driver())
            {
                case Connect::MYSQL:

                    foreach ($this->connexion->request('SHOW DATABASES') as $db)
                    {
                        $x = current($db);
                        if ($hidden->empty())
                        {
                            $databases->push($x);
                        } else {
                            if ($hidden->not_exist($x))
                                $databases->push($x);
                        }
                    }
                break;
                case Connect::POSTGRESQL:
                    foreach ($this->connexion->request('select datname from pg_database') as $db)
                    {
                        $x = current($db);
                        if ($hidden->empty())
                        {

                            $databases->push($x);
                        } else {
                            if ($hidden->not_exist($x))
                                $databases->push($x);
                        }
                    }
                break;
            }
            return $databases->collection();
        }

        /**
         * create database
         * @param string $database
         *
         * @return bool
         *
         * @throws Exception
         */
        public function create(string $database): bool
        {
            switch ($this->connexion->get_driver())
            {
                case Connect::MYSQL:
                    return empty($this->collation) && empty($this->encoding) ? $this->connexion->execute("CREATE DATABASE $database") :  $this->connexion->execute("CREATE DATABASE $database CHARACTER SET = '{$this->charset}'   COLLATE =  '{$this->collation}';");
                break;
                case Connect::POSTGRESQL:
                    return empty($this->collation) && empty($this->encoding) ? $this->connexion->execute("CREATE DATABASE $database  TEMPLATE template0") :  $this->connexion->execute("CREATE DATABASE  $database ENCODING '{$this->charset}' LC_COLLATE='{$this->collation}' LC_CTYPE='{$this->collation}' TEMPLATE template0;");
                break;
                case Connect::SQLITE:
                    return  new PDO("sqlite:$database",null,null) && chmod($database,0777);
                break;
                default:
                    return false;
                break;
            }
        }

        /**
         * set database charset
         *
         * @param string $charset
         *
         * @return Base
         */
        public function set_charset(string $charset): Base
        {
            $this->charset = $charset;

            return $this;
        }

        /**
         * set database collation
         *
         * @param string $collation
         *
         * @return Base
         *
         */
        public function set_collation(string $collation): Base
        {
            $this->collation = $collation;

            return $this;
        }

        /**
         * set database name
         *
         * @param string $name
         *
         * @return Base
         */
        public function set_name(string $name): Base
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
         * @throws \Exception
         */
        public function drop(string $database): bool
        {
            
            switch ($this->connexion->get_driver())
            {
                case Connect::SQLITE:
                    return File::delete($database);
                break;
                default:
                    return $this->connexion->execute("DROP DATABASE $database;");
                break;
            }
        }

        /**
         * dump a database
         *
         * @throws Exception
         */
        public function dump()
        {
            return dumper($this->connexion);
        }

        /**
         * verify if a table exist
         *
         * @param string $base
         *
         * @return bool
         *
         * @throws Exception
         */
        public function exist(string $base = ''): bool
        {
            return def($base) ? collection($this->show())->exist($base) : collection($this->show())->exist($this->database);
        }


        /**
         * get charset
         *
         * @return array
         *
         * @throws Exception
         */
        public function charsets(): array
        {
            $charset = collection();

            switch ($this->connexion->get_driver())
            {
                case Connect::MYSQL:
                    foreach ($this->connexion->request('SHOW CHARACTER SET') as $char)
                        $charset->push(current($char));
                break;
                case Connect::POSTGRESQL:
                    foreach ($this->connexion->request('SELECT DISTINCT pg_encoding_to_char(conforencoding) FROM pg_conversion ORDER BY 1') as $char)
                        $charset->push(current($char));
                break;
            }
            return $charset->collection();
        }

        /**
         * get the collation
         *
         * @return array
         *
         * @throws Exception
         */
        public function collations(): array
        {
            $collation = collection();
            switch ($this->connexion->get_driver())
            {
                case Connect::MYSQL:
                    foreach ($this->connexion->request('SHOW COLLATION') as $char)
                        $collation->push(current($char));
                break;
                case Connect::POSTGRESQL:
                    foreach ($this->connexion->request("SELECT collname FROM pg_collation") as $char)
                        $collation->push(current($char));
                break;
            }
            return $collation->collection();
        }

        /**
         * define hidden databases
         *
         * @param array $databases
         *
         * @return Base
         */
        public function hidden(array $databases): Base
        {
            $this->hidden = $databases;

            return $this;
        }

        /**
         * set the encoding option
         *
         * @param string $option
         *
         * @return Base
         */
        public function set_encoding_option(string $option): Base
        {
            $this->options = $option;

            return $this;
        }

        /**
         * drop all database
         *
         * @return bool
         *
         * @throws Exception
         */
        public function drop_all_databases(): bool
        {
           foreach ($this->show() as $base)
               if (!$this->drop($base))
                   return false;

           return empty($this->show());
        }

        /**
         * Database constructor.
         * 
         * @param Connect $connect
         */
        public function __construct(Connect $connect)
        {
            $this->connexion = $connect;
        }

        /**
         * define the fetch mode
         *
         * @param int $mode
         *
         * @return Base
         */
        public function set_fetch_mode(int $mode = PDO::FETCH_OBJ): Base
        {
           $this->fetch = $mode;

           return $this;
        }
    }
}
