<?php

namespace Imperium\Import {

    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\Exception\Kedavra;

    /**
    *
    * Import management
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
    class Import
    {

        /**
         *
         * the connexion
         *
         * @var Connect
         *
         */
        private $connexion;

        /**
         *
         * the sql file
         *
         * @var string
         */
        private $sql_file;

        /**
         *
         * The current driver
         *
         * @var string
         *
         */
        private $driver;

        /**
         *
         * The base name
         *
         * @var string
         *
         */
        private $base;

        /**
         * Import constructor.
         *
         * @throws Exception
         */
        public function __construct()
        {
            $this->connexion = app()->connect();
            $this->driver    = $this->connexion->driver();
            $this->base      = $this->connexion->base();
            $this->sql_file  = sql_file();
        }

        /**
         *
         * Import the data
         *
         * @method import
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function import(): bool
        {

            $password = $this->connexion->password();
            $username = $this->connexion->user();
            $host     = $this->connexion->host();
            $base     = $this->base;
            $sql      = $this->sql_file;

            switch ($this->driver)
            {
                case Connect::MYSQL:
                    return is_not_false(system("mysql -u $username -h $host -p$password $base < $sql"));
                break;
                case Connect::POSTGRESQL:
                    return  is_not_false(system(" psql  -h $host  -U $username $base < $sql"));
                break;
                case Connect::SQLITE:

                    return is_not_false(system("sqlite3 $base < $sql"));
                break;
                default:
                    return false;
                break;
            }
        }
    }
}
