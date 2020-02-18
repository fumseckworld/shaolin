<?php

declare(strict_types=1);

namespace Eywa\Database\Export {


    use Eywa\Console\Shell;
    use Eywa\Database\Connexion\Connexion;

    class Export
    {
        /**
         *
         */
        private Connexion $connexion;

        public function __construct(Connexion $connexion)
        {
            $this->connexion = $connexion;
        }

        public function dump(): bool
        {
            $password = $this->connexion->password();
            $username = $this->connexion->username();
            $host = $this->connexion->hostname();
            $base = $this->connexion->base();

            $file = base('db','dump',"$base.sql");
            switch($this->connexion->driver())
            {
                case MYSQL:
                    return (new Shell("mysqldump -u $username -p$password $base > $file"))->run();
                break;
                case POSTGRESQL:
                    return (new Shell("pg_dump -h $host  -U $username $base > $file"))->run();
                break;
                case SQLITE:
                    return (new Shell("sqlite3 $base  > $file"))->run();
                break;
                default:
                    return false;
                break;
            }


        }

    }
}