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

    /**
     * Management of base
     *
     * @package Imperium\Bases
     *
     */
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
            $driver = $this->driver;
            $this->check($driver);

            $databases = collection();

            $request = '';
            $hidden = def($this->hidden) ? collection($this->hidden) : collection();
            equal($driver,Connect::MYSQL) ?  assign(true,$request,'SHOW DATABASES') : assign(true,$request,'select datname from pg_database');

            foreach ($this->connexion->request($request) as $db)
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

            return $databases->collection();
        }


        /**
         *
         * Create multiple base
         *
         * @param string ...$bases
         *
         * @return bool
         *
         * @throws Exception
         */
        public function create_multiples(string ...$bases): bool
        {
            foreach ($bases as $x)
                is_not_true($this->create($x),true,"Failed to create the database : $x");

            return true;
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
            return equal($this->driver,Connect::SQLITE) ? File::remove($database) : $this->connexion->execute("DROP DATABASE $database");
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
            $driver = $this->driver;
            $this->check($driver);

            $request = '';

            equal(Connect::MYSQL,$driver) ? assign(true,$request,'SHOW CHARACTER SET') :  assign(true,$request,'SELECT DISTINCT pg_encoding_to_char(conforencoding) FROM pg_conversion ORDER BY 1');

            $charset = collection();

            foreach ($this->connexion->request($request) as $char)
                $charset->push(current($char));

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
            $driver = $this->driver;

            $this->check($driver);

            $request = '';

            equal($driver,Connect::MYSQL) ? assign(true,$request,'SHOW COLLATION') :  assign(true,$request,'SELECT collname FROM pg_collation');

            $collations = collection();

            foreach ($this->connexion->request($request) as $char)
                $collations->push(current($char));

            return $collations->collection();


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

            $driver = $this->driver;

            $this->check($driver);

            if (not_def($this->collation))
                throw new Exception("We have not found required collation");

            if (not_in($this->collations(),$this->collation))
                throw new Exception("Invalid collation name");

            return equal(Connect::MYSQL,$driver) ? $this->connexion->execute("ALTER DATABASE $base COLLATE = '{$this->collation}'") : $this->connexion->execute("update pg_database set datcollate='{$this->collation}', datctype='{$this->collation}' where datname = '$base'");

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

            $driver = $this->driver;

            $this->check($driver);

            if (not_def($this->charset))
                throw new Exception("We have not found required charset");

            if (not_in($this->charsets(),$this->charset))
                throw new Exception("Invalid charset name");

            return equal(Connect::MYSQL,$driver) ? $this->connexion->execute("ALTER DATABASE $base CHARACTER SET = {$this->charset}") : $this->connexion->execute("update pg_database set encoding = pg_char_to_encoding('{$this->charset}') where datname = '$base'");
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

        /**
         *
         * @param $driver
         *
         * @throws Exception
         */
        public function check($driver)
        {
            not_in([Connect::MYSQL, Connect::POSTGRESQL], $driver, true, "The current driver is not supported");
        }
    }
}
