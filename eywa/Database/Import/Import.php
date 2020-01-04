<?php

declare(strict_types=1);


namespace Eywa\Database\Import {


    use Eywa\Console\Shell;
    use Eywa\Database\Connection\Connect;
    use Eywa\Exception\Kedavra;

    class Import
    {

        /**
         *
         * The connection to the base
         *
         */
        private Connect $connect;
        /**
         * @var array|string[]
         */
        private array $files;

        /**
         * Import constructor.
         * @param Connect $connect
         * @param string ...$files
         */
        public function __construct(Connect $connect,string ...$files)
        {
            $this->connect = $connect;
            $this->files = $files;

        }

        /**
         *
         * Import files
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function import() : bool
        {

            $password = $this->connect->password();
            $username = $this->connect->user();
            $host = $this->connect->host();
            $base = $this->connect->base();
            $result = collect();
            $x =[];

            foreach ($this->files as $file)
            {
                switch($this->connect->driver())
                {
                    case MYSQL:


                        return (new Shell("mysqldump -u $username -p $base > sql/tmp.sql"))->run();
                    break;
                    case POSTGRESQL:
                        $result->push(is_not_false(system(" psql  -h $host  -U $username $base < $file",$x)));
                    break;
                    case SQLITE:
                        $result->push(is_not_false(system("sqlite3 $base < $file",$x)));
                    break;

                    case SQL_SERVER:
                        $result->push(is_not_false(system("sqlcmd -S $host -d $base -i $file",$x)));
                    break;
                    default:
                        return false;
                    break;
                }
            }
            $x ='';
            return $result->ok();
        }
    }
}