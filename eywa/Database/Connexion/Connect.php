<?php

declare(strict_types=1);

namespace Eywa\Database\Connexion {


    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use PDO;
    use PDOException;

    class Connect implements Connexion
    {

        /**
         *
         * The connection to the base
         *
         */
        private ?PDO $connexion = null;

        /**
         *
         * The queries to execute
         *
         * @var array<string>
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
        private int $port = 3306;


        /**
         *
         * The current base username
         *
         */
        private string $username;

        /**
         *
         * The current base password
         *
         */
        private string $password;

        /**
         *
         * The hostname
         *
         */
        private string $host = LOCALHOST;

        /**
         *
         * The sql query args
         * @var array<mixed>
         */
        private array $args = [];

        /**
         *
         * @Inject({"db.driver","db.name","db.username", "db.password","db.port","db.host"})
         *
         * @inheritDoc
         * 
         */
        public function __construct(string $driver, string $base,string $username ='',string $password ='',int $port = 3306,string $host = LOCALHOST)
        {

            $this->driver = $driver;
            $this->base = $base;
            $this->port = $port;
            $this->username = $username;
            $this->password = $password;
            $this->host = $host;
        }

        /**
         * @inheritDoc
         */
        public function set(string $query): Connexion
        {
            $this->queries = array_merge($this->queries,[$query]);
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
                    $x =  $this->pdo()->prepare($query);
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
        public function setup(): bool
        {
            return  true;
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

                    $result = $this->pdo()->prepare($query);

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
            return is_array($x) ? $x : [$x];
        }

        /**
         *
         * Check if class it's connected to the base
         *
         * @return bool
         *
         */
        public function connected(): bool
        {
            try {
                return  $this->pdo() instanceof PDO;
            }catch (Kedavra | PDOException $exception)
            {
                return false;

            }
        }

        /**
         * @param string ...$bases
         * @return bool
         * @throws Kedavra
         */
        public function create_database(string ...$bases): bool
        {
            $x = collect();
            foreach ($bases as $base)
                $x->push($this->set("CREATE DATABASE IF NOT EXISTS $base")->execute());

            return  $x->ok();
        }

        /**
         * @param string ...$bases
         * @return bool
         * @throws Kedavra
         */
        public function remove_database(string ...$bases): bool
        {
            $x = collect();
            if (in_array($this->driver(),[MYSQL,POSTGRESQL]))
            {
                foreach ($bases as $base)
                    $x->push($this->set("DROP DATABASE IF EXISTS $base")->execute());


            }else
            {
                foreach ($bases as $base)
                 $x->push(File::delete($base));
            }
            return  $x->ok();
        }

        /**
         *
         * @param string $user
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public function remove_user(string $user): bool
        {
            $x = collect();
            if (in_array($this->driver(),[MYSQL,POSTGRESQL]))
            {
                $this->mysql() ? $x->push($this->set("DROP USER IF EXISTS '$user'@'{$this->host}'")->execute()) : $x->push($this->set("DROP USER IF EXISTS $user")->execute());
            }
            return  $x->ok();
        }

        /**
         * @param string $user
         * @param string $password
         * @param string $base
         * @return bool
         * @throws Kedavra
         */
        public function create_user(string $user,string $password,string $base): bool
        {

            switch ($this->driver())
            {
                case MYSQL:
                    return $this->set("CREATE USER IF NOT EXISTS '$user'@'localhost' IDENTIFIED BY '$password';")->set("GRANT ALL PRIVILEGES ON $base.* TO '$user'@'localhost';")->execute();
                case POSTGRESQL:
                   return $this->set("CREATE USER $user WITH PASSWORD '$password' LOGIN;")->set("GRANT ALL PRIVILEGES ON DATABASE $base TO $user")->execute();
                default:
                    return false;
            }

        }
        /**
         * @inheritDoc
         */
        public function secure(string $x): string
        {
            return $this->pdo()->quote($x);
        }

        /**
         * @inheritDoc
         */
        public function commit(): bool
        {
            return $this->pdo()->commit();
        }

        /**
         *
         * Get the connexion infos
         *
         * @return string
         *
         */
        public function info(): string
        {

            return collect(get_object_vars($this))->del(['connexion','queries','args','options'])->each(function ($k,$v){
                return "\$$k=$v;";
            })->join('');

        }

        /**
         * @inheritDoc
         */
        public function back(string $message): bool
        {
            $this->queries = [];

            if ($this->pdo()->rollBack())
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
                    $result = $this->pdo()->prepare($query);
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
            $x = $this->pdo()->query($query);
            return is_bool($x) ? $x : $x->fetch(PDO::FETCH_COLUMN);
        }

        /**
         * @inheritDoc
         */
        public function development(): Connect
        {
            return new static(strval(env('DEVELOP_DB_DRIVER','mysql')),strval(env('DEVELOP_DB_NAME','ikran')),strval(env('DEVELOP_DB_USERNAME','ikran')),strval(env('DEVELOP_DB_PASSWORD','ikran')),intval(env('DEVELOP_DB_PORT',3306)),strval(env('DEVELOP_DB_HOST','localhost')));
        }

        /**
         * @return PDO
         * @throws Kedavra
         */
        private function pdo(): PDO
        {
            if (is_null($this->connexion))
            {
                $this->connexion = equal($this->driver,SQL_SERVER) ? new PDO("{$this->driver}:Serve={$this->host};Database={$this->base}",$this->username,$this->password,[]) : (equal($this->driver,SQLITE) ? new PDO("sqlite:{$this->base}") : new PDO("{$this->driver}:host={$this->host};port={$this->port};dbname={$this->base}", $this->username, $this->password, []));

                $this->connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                try {
                    $this->connexion->beginTransaction();
                }catch (PDOException $e)
                {
                    throw new Kedavra($e->getMessage());
                }
            }
            return $this->connexion;
        }
    }
}