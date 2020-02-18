<?php

declare(strict_types=1);


namespace Eywa\Database\Import {


    use Eywa\Console\Shell;
    use Eywa\Database\Connexion\Connexion;

    class Import
    {

        /**
         *
         * The connection to the base
         *
         */
        private Connexion $connect;


        /**
         * Import constructor.
         * @param Connexion $connect
         */
        public function __construct(Connexion $connect)
        {
            $this->connect = $connect;

        }

        /**
         *
         * Import files
         *
         * @return bool
         *
         */
        public function import() : bool
        {

            $password = $this->connect->password();
            $username = $this->connect->username();
            $host = $this->connect->hostname();
            $base = $this->connect->base();
            $file = base('db','dump',"$base.sql");


            if (!file_exists($file))
                return  false;

            switch($this->connect->driver())
            {
                case MYSQL:
                    return (new Shell("mysqldump  -h $host -u $username -p$password $base < $file"))->run();
                break;
                case POSTGRESQL:
                    return (new Shell("psql -h $host -U $username $base < $file"))->run();
                break;
                case SQLITE:
                    return (new Shell("sqlite3  $base < $file"))->run();
                break;
                default:
                    return false;
                break;
            }

        }
    }
}