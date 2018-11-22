<?php

namespace Imperium\Connexion {

    use Exception;
    use PDO;
    use PDOException;

    /**
    *
    * Management of the connections to bases
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
         * @param  string      $driver
         * @param  string      $base           The base's name
         * @param  string      $username       The base's username
         * @param  string      $password       The base's password
         * @param  string      $host           The base's host
         * @param  int         $pdo_fetch_mode The PDO fetch mode
         * @param  string      $dump_path      The path to dump directory
         *
         */
        public function __construct(string $driver,string $base,string $username,string $password,string $host,int $pdo_fetch_mode,string $dump_path)
        {
            $this->driver       = $driver;

            $this->database     = $base;

            $this->username     = $username;

            $this->password     = $password;

            $this->mode         = $pdo_fetch_mode;

            $this->dump_path    = $dump_path;

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
        public function get_host(): string
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
        public function get_driver(): string
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
        public function get_database(): string
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
        public function get_username(): string
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
        public function get_password(): string
        {
            return $this->password;
        }

        /**
         *
         * Return the current fecth mode
         *
         * @method get_fetch_mode
         *
         * @return int The PDO fecth mode
         *
         */
        public function get_fetch_mode(): int
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
        public function get_dump_path(): string
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
         */
        public function mysql(): bool
        {
            return equal($this->get_driver(),self::MYSQL);
        }

        /**
         *
         * Check if current driver is postgresql
         *
         * @method postgresql
         *
         * @return bool
         *
         */
        public function postgresql(): bool
        {
            return equal($this->get_driver(),self::POSTGRESQL);
        }

        /**
         *
         * Check if current driver is sqlite
         *
         * @method sqlite
         *
         * @return bool
         *
         */
        public function sqlite(): bool
        {
            return equal($this->get_driver(),self::SQLITE);
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

            if (is_string($instance))
                throw new Exception($instance);
            else
                return $instance;
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

            if (is_bool($query))
                throw new Exception("Invalid request : $request");

            $data = $query->fetchAll($this->get_fetch_mode());

            $query->closeCursor();

            return $data;
        }

       /**
        *
        * Execute a query and return true on success or false on faillure
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

            if (is_bool($response))
                throw new Exception("Invalid request : $request");

            $data = $response->execute();

            $response->closeCursor();

            return $data;

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
