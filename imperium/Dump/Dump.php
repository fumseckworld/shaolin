<?php

namespace Imperium\Dump {

    use Imperium\Connexion\Connect;
    use Imperium\Directory\Dir;
    use Imperium\File\File;
    use Imperium\Collection\Collection;


   /**
    *
    * Dump base content
    *
    * @author Willy Micieli <micieli@laposte.net>
    *
    * @package imperium
    *
    * @version 4
    *
    * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE&é
    *
    **/
    class Dump
    {
        /**
         *
         * Connection to the base
         *
         * @var Connect
         *
         */
        private $connexion;

        /**
         *
         * To dump a base
         *
         * @var bool
         *
         */
        private $base;

        /**
         *
         * The tables to dump
         *
         * @var Collection
         *
         */
        private $tables;

        /**
         *
         * The dump command
         *
         * @var string
         *
         */
        private $command;

        /**
         *
         * The quote to use
         *
         * @var string
         *
         */
        private $quote;


        /**
         *
         * Dumper constructor
         *
         * @method __construct
         *
         * @param  Connect     $connect The connexion to the base
         * @param  bool        $base    The option to dump the base
         * @param  array       $tables  The names of the tables
         *
         */
        public function __construct(Connect $connect,bool $base,array $tables)
        {
            $this->connexion = $connect;
            $this->base = $base;
            $this->tables = collection($tables);

            $this->command = '';
            $this->quote  = $this->determine_quote();
        }

        /**
         *
         * Dump a table or a base
         *
         * @method dump
         *
         * @return bool
         *
         **/
        public function dump(): bool
        {
            $database   = $this->connexion->base();
            $driver     = $this->connexion->driver();
            $password   = $this->connexion->password();
            $username   = $this->connexion->user();
            $dump_path  = $this->connexion->dump_path();
            $host       = $this->connexion->host();


            Dir::clear($dump_path);

            $filename =  "$dump_path/$database.sql";

            switch ($driver)
            {
                case Connect::MYSQL:
                    append($this->command,$this->quote,'mysqldump',$this->quote," -u$username", " -p$password");
                    if ($this->base)
                    {
                        append($this->command," $database > $filename");
                        system($this->command);
                    }else
                    {
                        $tables = $this->tables->join(' ');
                        append($this->command, " $database" ," --tables $tables"," > $filename");
                        system($this->command);
                    }
                break;
                case Connect::POSTGRESQL:
                    append($this->command,'pg_dump', " -U $username", " -h $host"," -d $database", ' -p 5432',' --clean',' --if-exists',' --inserts',' --no-owner');

                    if ($this->base)
                    {
                        append($this->command," > $filename");

                        system($this->command);
                        return File::exist($filename);

                    }else
                    {
                        append($this->command," -t");

                        append($this->command,$this->tables->join(' -t '));

                        append($this->command," > $filename");
                        system($this->command);
                    }
                break;
                case Connect::SQLITE:
                    append($this->command,"sqlite3");
                    if ($this->base)
                    {
                        append($this->command," $database .dump > $filename");
                        system($this->command);
                    }else
                    {
                        return false;
                    }
                break;
                default:
                    return false;
                break;
            }

            return File::exist($filename);
        }

        /**
         *
         *
         *
         * @method determine_quote
         *
         * @return string
         *
         */
        private function determine_quote(): string
        {
            return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '"' : "'";
        }
    }
}
