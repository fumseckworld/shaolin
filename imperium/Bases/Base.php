<?php

namespace Imperium\Bases {

    use Imperium\Exception\Kedavra;
    use PDO;
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
         *
         * Table management
         *
         * @var Table
         *
         **/
        private $table;

        /**
         *
         * Base Constructor
         *
         * @method __construct
         *
         * @param  Connect $connect
         * @param  Table $table
         *
         *
         */
        public function __construct(Connect $connect,Table $table )
        {
            $this->connexion        = $connect;

            $this->driver           = $connect->driver();

            $this->table            = $table;

        }


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
         * @throws Kedavra
         *
         */
        public function seed(int $records = 100): bool
        {
            foreach ($this->table->show() as $x)
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
         * @throws Kedavra
         */
        public function remove(string ...$bases): bool
        {
            $data = collect();

            foreach ($bases as $base)
            {
                if ($this->exist($base))
                    $data->push($this->drop($base));
            }
            return $data->ok();
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
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        public function copy(string $new_base): bool
        {
            dumper(true,[]);

            $this->create($new_base);

            return (new Import())->import();
        }

        /**
        *
        *  Display all bases
        *
        * @method show
        *
        * @return array
        *
        * @throws Kedavra
        *
        */
        public function show(): array
        {

           $this->check();

            $databases = collect();

            $request = '';

            $hidden =  collect($this->hidden_bases());

            $this->connexion->mysql() ?  assign(true,$request,'SHOW DATABASES') : assign(true,$request,'select datname from pg_database');

            foreach ($this->connexion->request($request) as $db)
            {
                $x = current($db);
                if ($hidden->empty())
                {
                    $databases->push($x);
                } else
                {
                    $databases->uniq($x);
                }
            }

            return $databases->all();
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @param string $name The base name
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function not_exist(string $name): bool
        {
            return collect($this->show())->not_exist($name);
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
         * @throws Kedavra
         */
        public function create(string ...$names): bool
        {
            if ($this->connexion->sqlite())
            {
                $data = collect();
                foreach ($names as $name)
                {
                    File::remove_if_exist($name);

                    new PDO("sqlite:$name",null,null);
                    $data->push(File::exist($name));
                }

                return $data->not_exist(false);
            }


            $not_define = not_def($this->collation,$this->charset);

            $data =  collect();

            foreach ($names as $name)
            {
                switch ($this->driver)
                {
                    case Connect::MYSQL:
                         $not_define ? $data->set($this->connexion->execute("CREATE DATABASE ?;",$name)) :  $data->set($this->connexion->execute("CREATE DATABASE $name CHARACTER SET = '?'   COLLATE =  '?';",$this->charset,$this->collation));
                    break;
                    case Connect::POSTGRESQL:
                        $not_define ? $data->set($this->connexion->execute("CREATE DATABASE ?  TEMPLATE template0;",$name)):  $data->set($this->connexion->execute("CREATE DATABASE $name ENCODING '?' LC_COLLATE='?' LC_CTYPE='?' TEMPLATE template0;",$this->charset,$this->collation,$this->collation));
                    break;
                }
            }

            return $data->ok();
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
         * @throws Kedavra
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
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        public function drop(string $name): bool
        {
            if ($this->connexion->sqlite())
                return File::remove_if_exist($name);

            return $this->exist($name) ? $this->connexion->execute("DROP DATABASE $name") : false;
        }

        /**
         *
         * Dump the current base
         *
         * @return  bool
         *
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        public function exist(string $base): bool
        {
            return  $this->connexion->sqlite() ? File::exist($base) : collect($this->show())->exist($base);
        }


        /**
         *
         * Display all charsets available
         *
         * @return array
         *
         * @throws Kedavra
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
         * @throws Kedavra
         *
         */
        public function collations(): array
        {
            $this->check();

            return collation($this->connexion);

        }




        /**
         *
         * Check if the server has bases
         *
         * @return bool
         *
         * @throws Kedavra
         */
        public function has(): bool
        {
            $this->check();

            return def($this->table->show());
        }

        /**
         *
         * Change the current base collation
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function change_collation(): bool
        {
            $base = $this->current();

            $this->check();

            is_true(not_def($this->collation),true,"We have not found required collation");

            return $this->connexion->mysql() ? $this->connexion->execute("ALTER DATABASE $base COLLATE = '?'",$this->collation) : $this->connexion->execute("update pg_database set datcollate='?', datctype='?' where datname = '$base'",$this->collation,$this->collation);

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
         * @throws Kedavra
         *
         */
        public function hidden_bases(): array
        {
            return db('hidden_bases');
        }

        /**
         *
         * Return all hidden table
         *
         * @return array
         *
         * @throws Kedavra
         *
         */
        public function hidden_tables(): array
        {
            return db('hidden_tables');
        }

        /**
         *
         * Change the current base charset
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function change_charset(): bool
        {
            $base = $this->current();

            $this->check();

            is_true(not_def($this->charset),true,"We have not found required charset");

            return $this->connexion->mysql() ? $this->connexion->execute("ALTER DATABASE $base CHARACTER SET ?;",$this->charset) : $this->connexion->execute("update pg_database set encoding = pg_char_to_encoding('?') where datname = '$base'",$this->charset);
        }

        /**
         *
         * Check if current driver is not sqlite
         *
         * @return Base
         *
         * @throws Kedavra
         *
         */
        public function check(): Base
        {
            not_in([MYSQL, POSTGRESQL], $this->driver, true, "The current driver is not supported");

            return $this;
        }

    }
}
