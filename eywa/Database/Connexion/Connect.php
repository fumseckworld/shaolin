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
        private string $host;

        /**
         *
         * The sql query args
         * @var array<mixed>
         */
        private array $args = [];

        /**
         *
         *
         * @inheritDoc
         *
         */
        public function __construct(string $driver, string $base, string $username ='', string $password ='', string $host = LOCALHOST)
        {
            $this->driver = $driver;
            $this->base = $base;
            $this->username = $username;
            $this->password = $password;
            $this->host = $host;
        }

        /**
         * @inheritDoc
         */
        public function set(string $query): Connexion
        {
            $this->queries = array_merge($this->queries, [$query]);
            return $this;
        }

        /**
         * @inheritDoc
         */
        public function execute(): bool
        {
            $result = collect();
            foreach ($this->queries as $query) {
                try {
                    $x = $this->pdo()->prepare($query);
                    $result->push($x->execute($this->args));
                    $x->closeCursor();
                } catch (PDOException $exception) {
                    return false;
                }
            }
            return $result->ok();
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

            foreach ($this->queries as $query) {
                try {
                    $result = $this->pdo()->prepare($query);

                    foreach ($this->args as $k => $arg) {
                        $result->bindParam($k +1, $arg);
                    }

                    $result->execute();
                    $x = $result->fetchAll($style);
                    $result->closeCursor();

                    $result = null;
                } catch (PDOException $exception) {
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
            } catch (Kedavra | PDOException $exception) {
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
            foreach ($bases as $base) {
                $x->push($this->set("CREATE DATABASE $base")->execute());
            }

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
            if (in_array($this->driver(), [MYSQL,POSTGRESQL])) {
                foreach ($bases as $base) {
                    $x->push($this->set("DROP DATABASE $base")->execute());
                }
            } else {
                foreach ($bases as $base) {
                    $x->push(File::delete($base));
                }
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
            if (in_array($this->driver(), [MYSQL,POSTGRESQL])) {
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
        public function create_user(string $user, string $password, string $base): bool
        {
            switch ($this->driver()) {
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
            return collect(get_object_vars($this))->del(['connexion','queries','args','options'])->each(function ($k, $v) {
                return "\$$k=$v;";
            })->join('');
        }

        /**
         * @inheritDoc
         */
        public function back(string $message): bool
        {
            $this->queries = [];

            if ($this->pdo()->rollBack()) {
                throw new Kedavra($message);
            }
            return false;
        }

        /**
         * @inheritDoc
         */
        public function available(): bool
        {
            return true;
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
        public function fetch(string $class = 'stdClass', array $args = []): array
        {
            $x = collect();

            foreach ($this->queries as $query) {
                try {
                    $result = $this->pdo()->prepare($query);
                    $result->execute($this->args);
                    $x->push($result->fetchObject($class, $args));
                    $result->closeCursor();

                    $result = null;
                } catch (PDOException $exception) {
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
        public function with(...$args): Connexion
        {
            $this->args = array_merge($this->args, $args);

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
            return new static(strval(env('DEVELOP_DB_DRIVER', 'mysql')),strval(env('DEVELOP_DB_NAME', 'ikran')),strval(env('DEVELOP_DB_USERNAME', 'ikran')),strval(env('DEVELOP_DB_PASSWORD', 'ikran')),strval(env('DEVELOP_DB_HOST', 'localhost')));
        }

        /**
         * @return PDO
         * @throws Kedavra
         */
        private function pdo(): PDO
        {
            if (is_null($this->connexion)) {
                if (equal($this->driver, MYSQL)) {
                    $this->connexion = def($this->base) ? new PDO(sprintf('mysql:host=%s;port=3306;dbname=%s;charset=UTF8', $this->host, $this->base), $this->username, $this->password) :  new PDO(sprintf('mysql:host=%s;port=3306;charset=UTF8', $this->host), $this->username, $this->password);
                } elseif (equal($this->driver, POSTGRESQL)) {
                    $this->connexion = def($this->base) ? new PDO(sprintf('pgsql:host=%s;port=5432;dbname=%s;options=\'--client_encoding=UTF8\'', $this->host, $this->base), $this->username, $this->password) :  new PDO(sprintf('pgsql:host=%s;port=5432;options=\'--client_encoding=UTF8\'', $this->host), $this->username, $this->password);
                } else {
                    $this->connexion =  new PDO(sprintf('sqlite:%s', $this->base), '', '');
                }

                $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $this->connexion;
        }
    }
}
