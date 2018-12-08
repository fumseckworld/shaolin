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
         * All hidden bases
         *
         * @var array
         *
         */
        private $hidden_bases;

        /**
         *
         * All hidden tables
         *
         * @var array
         *
         */
        private $hidden_tables;

       /**
        *
        * Create enregistrements in all tables not hidden
        *
        * @method seed
        *
        * @param  int   $records Number of records
        *
        * @return bool
        *
        * @throws Exception
        *
        */
        public function seed(int $records = 100): bool
        {
            foreach ($this->tables as $x)
                is_false($this->table->select($x)->seed($records),true,"Failed to seed the $x table");

            return true;
        }

        /**
         *
         * Rename a base
         *
         * @method rename
         *
         * @param  string $new_name The new name
         *
         * @return bool
         *
         */
        public function rename(string $new_name): bool
        {
            $current = $this->connexion->base();
            $this->copy($new_name);
            $connect = connect($this->connexion->driver(),$new_name,$this->connexion->user(),$this->connexion->password(),$this->connexion->host(),$this->connexion->fetch_mode(),$this->connexion->dump_path());
            $table   = table($connect);
            return (new static($connect,$table,$this->hidden_tables,$this->hidden_bases))->drop($current);
        }
        /**
         *
         * copy current database content in a new database
         *
         * @method copy
         *
         * @param  string $new_base [description]
         *
         * @return bool   [description]
         */
        public function copy(string $new_base): bool
        {
            dumper($this->connexion,true);

            $this->create($new_base);

            $sql = "{$this->connexion->dump_path()}/{$this->connexion->base()}.sql";

            return (new Import($this->connexion,$sql,$new_base))->import();
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
            $driver = $this->driver;

            $this->check($driver);

            $databases = collection();

            $request = '';

            $hidden =  collection($this->hidden_bases);

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
                    else
                        throw new Exception("Cannot create the base $x, the base already exist");
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
         * @param  string $name The base name
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function create(string $name): bool
        {
            if ($this->connexion->sqlite())
            {

                 if (File::exist($name))
                    File::remove($name);

                 new PDO("sqlite:$name",null,null) ;
                 return File::exist($name);
            }


            if($this->exist($name))
                $this->drop($name);

            $not_define = not_def($this->collation,$this->charset);

            switch ($this->driver)
            {
                case Connect::MYSQL:
                    return $not_define ? $this->connexion->execute("CREATE DATABASE $name") :  $this->connexion->execute("CREATE DATABASE $name CHARACTER SET = '{$this->charset}'   COLLATE =  '{$this->collation}';");
                break;
                case Connect::POSTGRESQL:
                    return $not_define ? $this->connexion->execute("CREATE DATABASE $name  TEMPLATE template0") :  $this->connexion->execute("CREATE DATABASE  $name ENCODING '{$this->charset}' LC_COLLATE='{$this->collation}' LC_CTYPE='{$this->collation}' TEMPLATE template0;");
                break;
                default:
                    return false;
                break;
            }
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

            return $this->connexion->execute("DROP DATABASE $name");
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
            return dumper($this->connexion,true);
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
            return  $this->connexion->sqlite() ? File::exist($base) : collection($this->all)->exist($base);
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
         * Base Constructor
         *
         * @method __construct
         *
         * @param  Connect     $connect
         * @param  Table       $table
         * @param  array       $hidden_tables
         * @param  array       $hidden_bases
         */
        public function __construct(Connect $connect,Table $table,array $hidden_tables = [], $hidden_bases = [] )
        {
            $this->hidden_tables    = $hidden_tables;
            $this->hidden_bases     = $hidden_bases;
            $this->connexion        = $connect;
            $this->driver           = $connect->driver();
            $this->tables           = $table->hidden($hidden_tables)->show();
            $this->table            = $table;
            if(different($this->driver,Connect::SQLITE))
                $this->all   = $this->hidden($hidden_bases)->show();
        }

        /**
         *
         * Define all hidden base
         *
         * @method hidden
         *
         * @param  array  $base
         *
         * @return Base
         *
         */
        public function hidden(array $base = []): Base
        {
            $this->hidden_bases = $base;

            return $this;
        }

        /**
         *
         * Check if the server has bases
         *
         * @return bool
         *
         */
        public function has(): bool
        {
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
            $base = $this->base();

            $driver = $this->driver;

            $this->check($driver);

            if (not_def($this->collation))
                throw new Exception("We have not found required collation");

            return equal(Connect::MYSQL,$driver) ? $this->connexion->execute("ALTER DATABASE $base COLLATE = '{$this->collation}'") : $this->connexion->execute("update pg_database set datcollate='{$this->collation}', datctype='{$this->collation}' where datname = '$base'");

        }

        /**
         * @return string
         */
        private function base(): string
        {
            return $this->connexion->base();
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
            $base = $this->base();

            $driver = $this->driver;

            $this->check($driver);

            if (not_def($this->charset))
                throw new Exception("We have not found required charset");

            return equal(Connect::MYSQL,$driver) ? $this->connexion->execute("ALTER DATABASE $base CHARACTER SET = {$this->charset}") : $this->connexion->execute("update pg_database set encoding = pg_char_to_encoding('{$this->charset}') where datname = '$base'");
        }

        /**
         *
         * Check if current driver is not sqlite
         *
         * @param string $driver
         *
         * @return Base
         *
         * @throws Exception
         *
         */
        public function check(string $driver): Base
        {
            not_in([Connect::MYSQL, Connect::POSTGRESQL], $driver, true, "The current driver is not supported");

            return $this;
        }

    }
}
