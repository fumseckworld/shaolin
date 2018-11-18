<?php

namespace Imperium\Connexion;

use Exception;
use PDO;
use PDOException;


class Connect
{

    const MYSQL = 'mysql';

    const POSTGRESQL = 'pgsql';

    const SQLITE = 'sqlite';

    const ORACLE = 'oci';

    /**
     * database name
     *
     * @var string
     */
    private $database;

    /**
     * database username
     *
     * @var string
     */
    private $username;

    /**
     * database password
     *
     * @var string
     */
    private $password;

    /**
     * php driver
     *
     * @var string
     */
    private $driver;

    /**
     * fetch mode
     *
     * @var int
     */
    private  $mode;

    /**
     * @var PDO
     */
    private $instance;

    /**
     * @var string
     */
    private $dump_path;

    /**
     * @var string
     */
    private $host;

    /**
     *
     * Connect constructor.
     *
     * @param string $driver
     * @param string $database
     * @param string $username
     * @param string $password
     * @param int $pdoFetchMode
     * @param string $dump_path
     *
     * @throws Exception
     */
    public function __construct(string $driver,string $database,string $username,string $password,string $host = 'localhost',int $pdoFetchMode = PDO::FETCH_OBJ,string $dump_path = 'dump')
    {
        $this->driver       = $driver;

        $this->database     = $database;

        $this->username     = $username;

        $this->password     = $password;

        $this->mode         = $pdoFetchMode;

        $this->dump_path    = $dump_path;
        
        $this->host         = $host;

        $this->instance     = $this->getInstance();
    }

    /**
     * 
     * Get hostname
     * 
     */
    public function get_host(): string
    {
        return $this->host;
    }

    /**
     * get the driver name
     *
     * @return string
     */
    public function get_driver(): string
    {
        return $this->driver;
    }

    /**
     * get the database name
     *
     * @return string
     */
    public function get_database(): string
    {
        return $this->database;
    }

    /**
     * get the username
     *
     * @return string
     */
    public function get_username(): string
    {
        return $this->username;
    }

    /**
     * get the password
     *
     * @return string
     */
    public function get_password(): string
    {
        return $this->password;
    }

    /**
     * get fetch mode
     *
     * @return int
     */
    public function get_fetch_mode(): int
    {
        return $this->mode;
    }

    /**
     * get dump path
     *
     * @return string
     */
    public function get_dump_path(): string
    {
        return $this->dump_path;
    }

    /**
     * determine if the driver used is mysql
     *
     * @return bool
     *
     * @throws Exception
     */
    public function mysql(): bool
    {
        return equal($this->get_driver(),self::MYSQL);
    }

    /**
     * determine if the driver used is postgresql
     *
     * @return bool
     * @throws Exception
     */
    public function postgresql(): bool
    {
        return equal($this->get_driver(),self::POSTGRESQL);
    }

    /**
     * determine if the driver used is postgresql
     *
     * @return bool
     *
     * @throws Exception
     */
    public function sqlite(): bool
    {
        return equal($this->get_driver(),self::SQLITE);
    }

    /**
     * get the pdo instance on success
     *
     * @throws Exception
     */
    public function instance(): PDO
    {
        $instance = $this->instance;
        if (is_string($instance))
            throw new Exception($instance);
        else
            return $instance;
    }
    /**
     * execute a query and return an array with data
     *
     * @param string $request
     *
     * @return array
     *
     * @throws Exception
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
     * execute a query and return an array with data
     *
     * @param string $request
     *
     * @return bool
     *
     * @throws Exception
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
            if (equal($driver,Connect::SQLITE))
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