<?php
/**
 * fumseck added Base.php to imperium
 * The 11/09/17 at 09:36
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General public License for more details.
 *
 * You should have received a copy of the GNU General public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package : imperium
 * @author  : fumseck
 **/


namespace Imperium\Bases {


    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\File\File;
    use PDO;

    class Base
    {
         


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
         * @var Connect 
         */
        private $connexion;
        
        /**
         * @var array
         */
        private $hidden;

        /**
         * @var string
         */
        private $driver;

        /**
         * @var string
         */
        private $database;

        /**
         *
         * show databases
         *
         * @return array
         *
         * @throws Exception
         * 
         */
        public function show(): array
        {
            $databases = collection();

            $hidden = def($this->hidden) ? collection($this->hidden) : collection();

            switch ($this->driver)
            {
                case Connect::MYSQL:

                    foreach ($this->connexion->request('SHOW DATABASES') as $db)
                    {
                        $x = current($db);
                        if ($hidden->empty())
                        {
                            $databases->push($x);
                        } else
                        {
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
                        } else
                        {
                            if ($hidden->not_exist($x))
                                $databases->push($x);
                        }
                    }
                break;
            }
            return $databases->collection();
        }

        /**
         *
         * Create the database
         *
         * @param string $database
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function create(string $database): bool
        {
            switch ($this->driver)
            {
                case Connect::MYSQL:
                    return not_def($this->collation,$this->charset) ? $this->connexion->execute("CREATE DATABASE $database") :  $this->connexion->execute("CREATE DATABASE $database CHARACTER SET = '{$this->charset}'   COLLATE =  '{$this->collation}';");
                break;
                case Connect::POSTGRESQL:
                    return not_def($this->collation,$this->charset) ? $this->connexion->execute("CREATE DATABASE $database  TEMPLATE template0") :  $this->connexion->execute("CREATE DATABASE  $database ENCODING '{$this->charset}' LC_COLLATE='{$this->collation}' LC_CTYPE='{$this->collation}' TEMPLATE template0;");
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
         *
         * Set database charset
         *
         * @param string $charset
         *
         * @return Base
         *
         */
        public function set_charset(string $charset): Base
        {
            $this->charset = $charset;

            return $this;
        }

        /**
         *
         * Set database collation
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
         *
         * Remove a database
         *
         * @param string $database
         *
         * @return bool
         *
         * @throws Exception
         */
        public function drop(string $database): bool
        {
            
            switch ($this->driver)
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
         *
         * Dump a database
         *
         * @return  bool
         *
         * @throws Exception
         *
         */
        public function dump(): bool
        {
            return dumper($this->connexion);
        }

        /**
         *
         * Check if a base exist
         *
         * @param string $base
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function exist(string $base): bool
        {
            return collection($this->show())->exist($base);
        }


        /**
         *
         * Display all charsets available
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function charsets(): array
        {
            $charset = collection();

            switch ($this->driver)
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
         *
         * Display all collations available
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function collations(): array
        {
            $collation = collection();
            switch ($this->driver)
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
         *
         * Define all hidden databases
         *
         * @param array $databases
         *
         * @return Base
         *
         */
        public function hidden(array $databases): Base
        {
            $this->hidden = $databases;

            return $this;
        }


        /**
         *
         * Base constructor.
         * 
         * @param Connect $connect
         *
         */
        public function __construct(Connect $connect)
        {
            $this->connexion = $connect;
            $this->driver = $connect->get_driver();
        }



        /**
         *
         * Check if the server has bases
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has(): bool
        {
            return def($this->show());
        }

        /**
         *
         * Change  the base collation
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function change_collation(): bool
        {
            $base = $this->base();

            if (not_def($this->collation))
                throw new Exception("We have not found required collation");

            if (not_in($this->collations(),$this->collation))
                throw new Exception("Invalid collation name");

            switch ($this->driver)
            {
                case Connect::MYSQL;
                    return $this->connexion->execute("ALTER DATABASE $base COLLATE = '{$this->collation}'");
                break;
                case Connect::POSTGRESQL:
                    return  $this->connexion->execute("update pg_database set datcollate='{$this->collation}', datctype='{$this->collation}' where datname = '$base'");
                break;
                default:
                    return false;
                break;
            }
        }

        /**
         * @return string
         */
        private function base(): string
        {
            return def($this->database) ?  $this->database:  $this->connexion->get_database();
        }


        /**
         *
         * Change charset
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function change_charset(): bool
        {
            $base = $this->base();

            if (not_def($this->charset))
                throw new Exception("We have not found required charset");

            if (not_in($this->charsets(),$this->charset))
                throw new Exception("Invalid charset name");

            switch ($this->driver)
            {
                case Connect::MYSQL;
                    return $this->connexion->execute("ALTER DATABASE $base CHARACTER SET = {$this->charset}");
                break;
                case Connect::POSTGRESQL:
                    return $this->connexion->execute("update pg_database set encoding = pg_char_to_encoding('{$this->charset}') where datname = '$base'");
                break;
                default:
                    return false;
                break;
            }
        }

        /**
         *
         * @param string $base
         *
         * @return Base
         */
        public function set_name(string $base): Base
        {
            $this->database = $base;

            return $this;
        }
    }
}
