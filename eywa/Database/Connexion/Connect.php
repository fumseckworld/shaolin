<?php

declare(strict_types=1);

namespace Eywa\Database\Connexion {


    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use PDO;
    use PDOException;

    class Connect implements Connexion
    {

        /**
         *
         * The connection to the base
         *
         */
        private PDO $connexion;

        /**
         *
         * The queries to execute
         *
         */
        private array $queries = [];

        /**
         *
         * The current driver
         *
         */
        private string $driver;

        /**
         *
         * The current base name
         *
         */
        private string $base;

        /**
         *
         * The current base port
         *
         */
        private ?int $port = null;


        /**
         *
         * The current base username
         *
         */
        private ?string $username = null;

        /**
         *
         * The current base password
         *
         */
        private ?string $password  = null;

        /**
         *
         * The connexion options
         *
         */
        private array $options = [];

        /**
         *
         * The hostname
         *
         */
        private string $host = LOCALHOST;

        /**
         *
         * The sql query args
         *
         */
        private array $args = [];

        /**
         *
         * @Inject({"db.driver","db.name","db.username", "db.password","db.port","db.options","db.host"})
         *
         * @inheritDoc
         * 
         */
        public function __construct(string $driver, string $base,string $username ='',string $password ='',int $port = 3306, array $options = [], string $host = LOCALHOST)
        {
            $this->connexion = equal($driver,SQL_SERVER) ? new PDO("$driver:Serve=$host;Database=$base",$username,$password,$options) : (equal($driver,SQLITE) ? new PDO("sqlite:$base") : new PDO("$driver:host=$host;port=$port;dbname=$base", $username, $password, $options));

            $this->connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

            try {
                $this->connexion->beginTransaction();
            }catch (PDOException $e)
            {
                throw new Kedavra($e->getMessage());
            }

            $this->driver = $driver;
            $this->base = $base;
            $this->port = $port;
            $this->username = $username;
            $this->password = $password;
            $this->options = $options;
            $this->host = $host;
        }

        /**
         * @inheritDoc
         */
        public function set(string ...$queries): Connexion
        {
            $this->queries = array_merge($this->queries,$queries);
            return $this;
        }

        /**
         * @inheritDoc
         */
        public function execute(): bool
        {
            foreach ($this->queries as $query)
            {
                try {
                    $x =  $this->connexion->prepare($query);
                    $x->execute($this->args);
                    $x->closeCursor();
                }catch (PDOException $exception)
                {
                    return $this->back($exception->getMessage());
                }
            }

            return $this->commit();
        }

        /**
         * @inheritDoc
         */
        public function get(int $style): array
        {
            $x = [];

            foreach ($this->queries as $query)
            {

                try
                {

                    $result = $this->connexion->prepare($query);

                    foreach ($this->args as $k => $arg)
                        $result->bindParam($k +1,$arg);

                    $result->execute();
                    $x = $result->fetchAll($style);
                    $result->closeCursor();

                    $result = null;
                }catch (PDOException $exception)
                {
                    $this->back($exception->getMessage());
                    return [];
                }
            }
            return $x;
        }

        /**
         * @inheritDoc
         */
        public function secure(string $x): string
        {
            return $this->connexion->quote($x);
        }

        /**
         * @inheritDoc
         */
        public function commit(): bool
        {
            return $this->connexion->commit();
        }

        /**
         * @inheritDoc
         */
        public function back(string $message): bool
        {
            $this->queries = [];

            if ($this->connexion->rollBack())
            {
                throw new Kedavra($message);
            }
            return false;
        }

        /**
         * @inheritDoc
         */
        public function available(): bool
        {
            return collect([MYSQL,POSTGRESQL,SQLITE,SQL_SERVER])->exist($this->driver());
        }

        /**
         * @inheritDoc
         */
        public function driver(): string
        {
            return $this->driver;
        }

        /**
         * @inheritDoc
         */
        public function base(): string
        {
            return  $this->base;
        }

        /**
         * @inheritDoc
         */
        public function username(): string
        {
            return  $this->username;
        }

        /**
         * @inheritDoc
         */
        public function password(): string
        {
            return $this->password;
        }

        /**
         * @inheritDoc
         */
        public function sql(): Collect
        {
            return collect($this->queries);
        }

        /**
         * @inheritDoc
         */
        public function fetch(string $class = 'stdClass',array $args = []): array
        {

            $x = collect();

            foreach ($this->queries as $query)
            {

                try
                {
                    $result = $this->connexion->prepare($query);
                    $result->execute($this->args);
                    $x->push($result->fetchObject($class,$args));
                    $result->closeCursor();

                    $result = null;
                }catch (PDOException $exception)
                {
                    $this->back($exception->getMessage());
                    return [];
                }
            }
            return $x->all();
        }

        /**
         *
         * Check if the driver is mysql
         *
         * @return bool
         *
         */
        public function mysql(): bool
        {
            return $this->driver() === MYSQL;
        }

        /**
         *
         * Check if the driver is postgresql
         *
         * @return bool
         *
         */
        public function postgresql(): bool
        {
            return $this->driver() === POSTGRESQL;
        }

        /**
         *
         * Check if the driver is sqlite
         *
         * @return bool
         *
         */
        public function sqlite():bool
        {
            return $this->driver() === SQLITE;
        }

        /**
         *
         * Check if the driver is sql server
         *
         * @return bool
         *
         */
        public function sql_server(): bool
        {
            return $this->driver() === SQL_SERVER;
        }

        /**
         *
         * Check if the driver is not the driver
         *
         * @param string $driver
         *
         * @return bool
         *
         */
        public function not(string $driver): bool
        {
            return  $this->driver() !== $driver;
        }

        /**
         * @inheritDoc
         */
        public function hostname(): string
        {
            return $this->host;
        }

        /**
         * @inheritDoc
         */
        public function port(): int
        {
            return $this->port;
        }

        /**
         * @inheritDoc
         */
        public function with(...$args): Connexion
        {

            $this->args = array_merge($this->args,$args);

            return  $this;
        }

        /**
         * @inheritDoc
         */
        public function query(string $query)
        {
            $x = $this->connexion->query($query);

            return $x->fetch(PDO::FETCH_COLUMN);
        }
    }
}