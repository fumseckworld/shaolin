<?php

namespace Imperium\Bases {

use PDO;
use Exception;
use Imperium\Connexion\Connect;
use Imperium\File\File;
use Imperium\Import\Import;
use Imperium\Tables\Table;

    /**
    *
    * Bases management
    *
    * @author Willy Micieli <micieli@laposte.net>
    *
    * @package imperium
    *
    * @version 4
    *
    * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE
    *
    **/
    class Base
    {
        /**
         *
         * The base collation
         *
         * @var string
         *
         */
        private $collation;

        /**
         *
         * The base charset
         *
         * @var string
         *
         */
        private $charset;

       /**
        *
        * The connexion to the base
        *
        * @var Connect
        *
        */
        private $connexion;

        /**
         *
         * The current driver used
         *
         * @var string
         *
         */
        private $driver;

        /**
         * Tables
         *
         * @var array
         *
         */
        private $tables;

        /**
         *
         * Table management
         *
         * @var Table
         *
         **/
        private $table;

        /**
         *
         * All bases
         *
         * @var array
         *
         */
        private $all;

        /**
         *
         * Create records in all tables not hidden
         *
         * @method seed
         *
         * @param int $records Number of records
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function seed(int $records = 100): bool
        {
            foreach ($this->tables as $x)
                is_false($this->table->from($x)->seed($records),true,"Failed to seed the $x table");

            return true;
        }

        /**
         *
         * Remove bases if exist
         *
         * @param string[]  $bases
         *
         * @return bool
         *
         * @throws Exception
         */
        public function remove(string ...$bases): bool
        {
            $data = collection();

            foreach ($bases as $base)
            {
                if ($this->exist($base))
                    $data->add($this->drop($base));
            }
            return $data->not_exist(false);
        }
        /**
         *
         * Rename a base
         *
         * @method rename
         *
         * @param  string $base
         * @param  string $new_name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function rename(string $base,string $new_name): bool
        {
            $this->copy($new_name);

            $connect = connect($this->connexion->driver(),$new_name,$this->connexion->user(),$this->connexion->password(),$this->connexion->host(),$this->connexion->dump_path());

            $table   = table($connect);

            return (new static($connect,$table))->drop($base);
        }

        /**
         *
         * copy current database content in a new database
         *
         * @method copy
         *
         * @param  string $new_base
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function copy(string $new_base): bool
        {
            dumper(true);

            $this->create($new_base);

            return (new Import('',$new_base))->import();
        }

        /**
        *
        *  Display all bases
        *
        * @method show
        *
        * @return array
        *
        * @throws Exception
        *
        */
        public function show(): array
        {

           $this->check();

            $databases = collection();

            $request = '';

            $hidden =  collection($this->hidden_bases());

            $this->connexion->mysql() ?  assign(true,$request,'SHOW DATABASES') : assign(true,$request,'select datname from pg_database');

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
         * Remove multiples bases
         *
         * @method drop_multiples
         *
         * @param  string[] $names Bases to remove
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function drop_multiples(string ...$names): bool
        {
            foreach ($names as $x)
                is_false($this->drop($x),true,"Failed to remove the database : $x");

            return true;
        }

        /**
         *
         *  Create multiples bases
         *
         * @method create_multiples
         *
         * @param  string[] $names Bases to create
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function create_multiples(string ...$names): bool
        {
            if ($this->connexion->sqlite())
            {
                foreach ($names as $x)
                {
                    is_true(File::exist($x),true,"Cannot create the base $x, the base already exist");

                    is_false($this->create($x),true,"Failed to create the database : $x");
                }
            }else
            {
                foreach ($names as $x)
                {
                    if ($this->not_exist($x))
                        is_false($this->create($x),true,"Failed to create the database : $x");

                }
            }

            return true;
        }

        /**
         *
         *  Check if a base not exist
         *
         * @method not_exist
         *
         * @param  string $name The base name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function not_exist(string $name): bool
        {
            return collection($this->all)->not_exist($name);
        }

        /**
         *
         *  Create a base
         *
         * @method create
         *
         * @param string[] $names
         * @return bool
         *
         * @throws Exception
         */
        public function create(string ...$names): bool
        {
            if ($this->connexion->sqlite())
            {
                $data = collection();
                foreach ($names as $name)
                {
                    File::remove_if_exist($name);

                    new PDO("sqlite:$name",null,null);
                    $data->add(File::exist($name));
                }

                return $data->not_exist(false);
            }


            $not_define = not_def($this->collation,$this->charset);

            $data =  collection();

            foreach ($names as $name)
            {
                switch ($this->driver)
                {
                    case Connect::MYSQL:
                         $not_define ? $data->add($this->connexion->execute("CREATE DATABASE $name;")) :  $data->add($this->connexion->execute("CREATE DATABASE $name CHARACTER SET = '{$this->charset}'   COLLATE =  '{$this->collation}';"));
                    break;
                    case Connect::POSTGRESQL:
                        $not_define ? $data->add($this->connexion->execute("CREATE DATABASE $name  TEMPLATE template0;")):  $data->add($this->connexion->execute("CREATE DATABASE  $name ENCODING '{$this->charset}' LC_COLLATE='{$this->collation}' LC_CTYPE='{$this->collation}' TEMPLATE template0;"));
                    break;
                }
            }

            return $data->not_exist(false);
        }

        /**
         *
         * Set the base charset
         *
         * @method set_charset
         *
         * @param  string $charset The charset to use
         *
         * @return Base
         *
         * @throws Exception
         *
         */
        public function set_charset(string $charset): Base
        {
            $this->check();

            not_in($this->charsets(),$charset,true,"The charset is not valid");

            $this->charset = $charset;

            return $this;
        }

        /**
         *
         * Set the base collation
         *
         * @method set_collation
         *
         * @param  string  $collation The collation to use
         *
         * @return Base
         *
         * @throws Exception
         *
         */
        public function set_collation(string $collation): Base
        {
            $this->check();

            not_in($this->collations(),$collation,true,"The collation is not valid");

            $this->collation = $collation;

            return $this;
        }

        /**
         *
         * Remove a database
         *
         * @param string $name The base name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function drop(string $name): bool
        {
            if ($this->connexion->sqlite())
                return File::exist($name) ? File::remove($name): false;

            return $this->exist($name) ? $this->connexion->execute("DROP DATABASE $name") : false;
        }

        /**
         *
         * Dump the current base
         *
         * @return  bool
         *
         * @throws Exception
         *
         */
        public function dump(): bool
        {
            return dumper(true);
        }

        /**
         *
         * Verify if a base exist
         *
         * @param string $base The base name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function exist(string $base): bool
        {
            return  $this->connexion->sqlite() ? File::exist($base) : collection($this->show())->exist($base);
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
            $this->check();

            return charset($this->connexion);
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
            $this->check();

            return collation($this->connexion);

        }

        /**
         *
         * Base Constructor
         *
         * @method __construct
         *
         * @param  Connect $connect
         * @param  Table $table
         *
         * @throws Exception
         *
         */
        public function __construct(Connect $connect,Table $table )
        {
            $this->connexion        = $connect;
            $this->driver           = $connect->driver();
            $this->tables           = $table->show();
            $this->table            = $table;

            if(different($this->driver,Connect::SQLITE))
                $this->all   = $this->show();
        }



        /**
         *
         * Check if the server has bases
         *
         * @return bool
         *
         * @throws Exception
         */
        public function has(): bool
        {
            $this->check();

            return def($this->all);
        }

        /**
         *
         * Change the current base collation
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function change_collation(): bool
        {
            $base = $this->current();

            $this->check();

            if (not_def($this->collation))
                throw new Exception("We have not found required collation");

            return equal(Connect::MYSQL,$this->driver) ? $this->connexion->execute("ALTER DATABASE $base COLLATE = '{$this->collation}'") : $this->connexion->execute("update pg_database set datcollate='{$this->collation}', datctype='{$this->collation}' where datname = '$base'");

        }

        /**
         * @return string
         */
        public function current(): string
        {
            return $this->connexion->base();
        }

        /**
         *
         * Return all hidden bases
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function hidden_bases(): array
        {
            return config('db','hidden_bases');
        }

        /**
         *
         * Return all hidden table
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function hidden_tables(): array
        {
            return config('db','hidden_tables');
        }

        /**
         *
         * Change the current base charset
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function change_charset(): bool
        {
            $base = $this->current();

            $this->check();

            if (not_def($this->charset))
                throw new Exception("We have not found required charset");

            return equal(Connect::MYSQL,$this->driver) ? $this->connexion->execute("ALTER DATABASE $base CHARACTER SET = {$this->charset}") : $this->connexion->execute("update pg_database set encoding = pg_char_to_encoding('{$this->charset}') where datname = '$base'");
        }

        /**
         *
         * Check if current driver is not sqlite
         *
         * @return Base
         *
         * @throws Exception
         *
         */
        public function check(): Base
        {
            not_in([Connect::MYSQL, Connect::POSTGRESQL], $this->driver, true, "The current driver is not supported");

            return $this;
        }

    }
}
