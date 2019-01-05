<?php

namespace Imperium\Connexion {

    use Exception;
    use PDO;
    use PDOException;
    use Imperium\Directory\Dir;

   /**
    *
    * Management of the connections to the bases
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
    class Connect
    {
        /**
         *
         *
         * @var string
         *
         */
        const MYSQL = 'mysql';

        /**
         *
         * @var string
         *
         */
        const POSTGRESQL = 'pgsql';

        /**
         *
         * @var string
         *
         */
        const SQLITE = 'sqlite';

        /**
         *
         * @var string
         *
         */
        const ORACLE = 'oci';

        /**
         *
         *  @var string
         *
         */
        const LOCALHOST = 'localhost';

        /**
         *
         * The pdo mode
         *
         * @var int
         *
         */
        const PDO_MODE = PDO::FETCH_OBJ;

        /**
         *
         * The base's name
         *
         * @var string
         *
         */
        private $database;

        /**
         *
         * The base's username
         *
         * @var string
         *
         */
        private $username;

        /**
         *
         * The base's password
         *
         * @var string
         *
         */
        private $password;

        /**
         *
         * The base's driver
         *
         * @var string
         *
         */
        private $driver;

        /**
         *
         * The PDO fetch mode
         *
         * @var int
         *
         */
        private $mode;

        /**
         *
         * The pdo instance
         *
         * @var PDO
         *
         */
        private $instance;

        /**
         *
         * The dump directory path
         *
         * @var string
         *
         */
        private $dump_path;

        /**
         *
         * The connection hostname
         * can be a remote ip
         *
         * @var string
         *
         */
        private $host;

        /**
         *
         * Create a PDO connection
         *
         * @method __construct
         *
         * @param  string $driver
         * @param  string $base The base's name
         * @param  string $username The base's username
         * @param  string $password The base's password
         * @param  string $host The base's host
         * @param  string $dump_path The path to dump directory
         *
         * @throws Exception
         *
         */
        public function __construct(string $driver,string $base,string $username,string $password,string $host,string $dump_path)
        {
            Dir::create($dump_path);

            $this->driver       = $driver;

            $this->database     = $base;

            $this->username     = $username;

            $this->password     = $password;

            $this->mode         = self::PDO_MODE;     

            $this->dump_path    = realpath($dump_path);

            $this->host         = $host;

            $this->instance     = $this->getInstance();
        }


        /**
         *
         * Return the current host used
         *
         * @method get_host
         *
         * @return string   The defined host
         */
        public function host(): string
        {
            return $this->host;
        }

        /**
         *
         * Return the current driver used
         *
         * @method get_driver
         *
         * @return string The current driver
         *
         */
        public function driver(): string
        {
            return $this->driver;
        }

       /**
        *
        * Return the current base used
        *
        * @method get_database
        *
        * @return string  the current base
        *
        */
        public function base(): string
        {
            return $this->database;
        }

        /**
         *
         * Return the current username used
         *
         * @method get_username
         *
         * @return string The current username
         *
         **/
        public function user(): string
        {
            return $this->username;
        }

        /**
         *
         * Return the current password
         *
         * @method get_password
         *
         * @return string The current password
         *
         */
        public function password(): string
        {
            return $this->password;
        }

        /**
         *
         * Return the current fetch mode
         *
         * @method get_fetch_mode
         *
         * @return int The PDO  fetch mode
         *
         */
        public function fetch_mode(): int
        {
            return $this->mode;
        }

        /**
         *
         * Return the dump directory path
         *
         * @method get_dump_path
         *
         * @return string The dump path
         */
        public function dump_path(): string
        {
            return $this->dump_path;
        }

        /**
         *
         * Check if current driver is mysql
         *
         * @method mysql
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function mysql(): bool
        {
            return equal($this->driver(),self::MYSQL);
        }

        /**
         *
         * Check if current driver is postgresql
         *
         * @method postgresql
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function postgresql(): bool
        {
            return equal($this->driver(),self::POSTGRESQL);
        }

        /**
         *
         * Check if current driver is sqlite
         *
         * @method sqlite
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function sqlite(): bool
        {
            return equal($this->driver(),self::SQLITE);
        }

       /**
        *
        * Return the PDO instance on success
        *
        * @method instance
        *
        * @return PDO  the instance
        *
        * @throws Exception
        *
        **/
        public function instance(): PDO
        {
            $instance = $this->instance;

            if(is_string($instance))
                throw new Exception($instance);

            return $instance;

        }

        /**
         *
         * Check if the driver is not the current
         *
         * @method not
         *
         * @param string $driver
         *
         * @return bool
         *
         * @throws Exception
         */
        public function not(string  $driver): bool
        {
            return different($driver,$this->driver());
        }

        /**
         *
         * Execute a request and return result in an array
         *
         * @method request
         *
         * @param  string  $request The sql query
         *
         * @return array
         *
         * @throws Exception
         *
         */
        public function request(string $request): array
        {
            $query = $this->instance()->query($request);

            is_true(is_bool($query),true,$request);

            $data = $query->fetchAll($this->fetch_mode());

            is_false($query->closeCursor(),true,"Fail to close the connection");

            return $data;
        }

       /**
        *
        * Execute a query and return true on success or false on failure
        *
        * @method execute
        *
        * @param  string  $request The sql query
        *
        * @return bool
        *
        * @throws Exception
        *
        */
        public function execute(string $request): bool
        {
            $response = $this->instance->prepare($request);

            is_true(is_bool($response),true,$request);

            $data = $response->execute();

            is_false($response->closeCursor(),true,"Fail to close the connection");

            return $data;

        }

        /**
         *
         * Start a transaction block
         *
         * @return Connect
         *
         * @throws Exception
         *
         */
        public function transaction(): Connect
        {
            is_false($this->instance()->beginTransaction(),true,"Transaction start fail");

            return $this;
        }

        /**
         *
         * Commit the current transaction
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function commit(): bool
        {
            return $this->instance()->commit();
        }

        /**
         *
         * Execute the queries
         *
         * @param string ...$queries
         *
         * @return Connect
         *
         * @throws Exception
         *
         */
        public function queries(string ...$queries): Connect
        {
            foreach ($queries as $query)
                is_false($this->execute($query),true,$query);

            return $this;

        }

        /**
         *
         * Abort the current transaction
         *
         * @return Connect
         *
         * @throws Exception
         *
         */
        public function rollback(): Connect
        {
           is_false($this->instance()->rollBack(),true,"ROLLBACK as fail");

            return $this;
        }

        /**
         *
         * @return string|PDO
         *
         * @throws Exception
         */
        private function getInstance()
        {
            $database   = $this->database;
            $username   = $this->username;
            $password   = $this->password;
            $driver     = $this->driver;
            $host       = $this->host;

            if (is_null($this->instance))
            {
                if ($this->sqlite())
                {
                    if (def($database))
                    {
                        try
                        {
                            return new PDO("$driver:$database");
                        }catch (PDOException $e)
                        {
                            return $e->getMessage();
                        }
                    }else{
                        try
                        {
                            return new PDO("$driver::memory:");
                        }catch (PDOException $e)
                        {
                            return $e->getMessage();
                        }
                    }

                }
                if (def($database))
                {
                    try
                    {
                        return new PDO("$driver:host=$host;dbname=$database",$username,$password);
                    }catch (PDOException $e)
                    {
                        return $e->getMessage();
                    }
                }
                else
                {
                    try
                    {
                        return new PDO( "$driver:host=$host;",$username,$password);
                    }catch (PDOException $e)
                    {
                        return $e->getMessage();
                    }
                }
            }
           return $this->instance;

        }
    }
}
