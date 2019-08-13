<?php
	
	namespace Imperium\Connexion
	{
		
		use Exception;
		use Imperium\Exception\Kedavra;
		use PDO;
		use PDOException;

        /**
		 *
		 * Class Connect
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Connexion
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Connect
		{
			
			/**
			 *
			 * The base name
			 *
			 * @var string
			 *
			 */
			private $database;
			
			/**
			 *
			 * The username
			 *
			 * @var string
			 *
			 */
			private $username;
			
			/**
			 *
			 * The password
			 *
			 * @var string
			 *
			 */
			private $password;
			
			/**
			 *
			 * The  driver
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
			private $mode = PDO::FETCH_OBJ;
			
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
			 *
			 * @var string
			 *
			 */
			private $host = LOCALHOST;
			
			/**
			 *
			 * Create a PDO connection
			 *
			 * @Inject({"db.driver","db.name","db.username", "db.password","db.host", "db.dump"})
			 *
			 * @method __construct
			 *
			 * @param  string  $driver
			 * @param  string  $base       The base's name
			 * @param  string  $username   The base's username
			 * @param  string  $password   The base's password
			 * @param  string  $host       The base's host
			 * @param  string  $dump_path  The path to dump directory
			 *
			 *
			 */
			public function __construct( string $driver, string $base, string $username, string $password, string $host, string $dump_path )
			{
				
				$this->dump_path = DB . DIRECTORY_SEPARATOR . $dump_path;
				
				$this->driver = $driver;
				
				$this->database = $base;
				
				$this->username = $username;
				
				$this->password = $password;
				
				$this->host = $host;
			}
			
			/**
			 *
			 * Change the pdo mode
			 *
			 * @method set
			 *
			 * @param  int  $pdo_mode
			 *
			 * @return Connect
			 *
			 */
			public function set(int $pdo_mode): Connect
			{
				$this->mode = $pdo_mode;
				
				return $this;
			}

            /**
             *
             * Execute all queries
             *
             * @method queries
             *
             * @param string ...$queries
             *
             * @return bool
             */
			public function queries( string ...$queries ) : bool
			{
				return collect($queries)->for([ $this, 'execute' ])->ok();
			}


			/**
			 *
			 * Return the current host used
			 *
			 * @method host
			 *
			 * @return string
			 *
			 */
			public function host() : string
			{
				return $this->host;
			}
			
			/**
			 *
			 * Return the current driver used
			 *
			 * @method driver
			 *
			 * @return string
			 *
			 */
			public function driver() : string
			{
				return $this->driver;
			}
			
			/**
			 *
			 * Return the current base used
			 *
			 * @method base
			 *
			 * @return string
			 *
			 */
			public function base() : string
			{
				return $this->database;
			}
			
			/**
			 *
			 * Return the current username
			 *
			 * @method user
			 *
			 * @return string
			 *
			 **/
			public function user() : string
			{
				return $this->username;
			}
			
			/**
			 *
			 * Return the current password
			 *
			 * @method password
			 *
			 * @return string The current password
			 *
			 */
			public function password() : string
			{
				return $this->password;
			}
			
			/**
			 *
			 * Return the current fetch mode
			 *
			 * @method fetch_mode
			 *
			 * @return int
			 *
			 */
			public function fetch_mode() : int
			{
				return $this->mode;
			}
			
			/**
			 *
			 * Return the dump directory path
			 *
			 * @method dump_path
			 *
			 * @return string
			 *
			 */
			public function dump_path() : string
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
			public function mysql() : bool
			{
				return $this->driver() === MYSQL;
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
			public function postgresql() : bool
			{
				return $this->driver() === POSTGRESQL;
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
			public function sqlite() : bool
			{
				return $this->driver() === SQLITE;
			}
			
			/**
			 *
			 * Return the PDO instance on success
			 *
			 * @method instance
			 *
			 * @throws Kedavra
			 *
			 * @return PDO
			 *
			 */
			public function pdo() : PDO
			{
				$instance = $this->getpdo();
				
				if (is_string($instance))
					throw new Kedavra($instance);
				
				return $instance;
				
			}
			
			/**
			 *
			 * @param  string    $sql
			 * @param  string[]  $vars
			 *
			 * @throws Kedavra
			 *
			 * @return object
			 *
			 */
			public function fetch(string $sql, string ...$vars )
			{
				$query = $this->pdo()->prepare($sql);
				
				is_true(is_bool($query), true, $sql);
				
				$query->execute($vars);
				
				$x = $query->fetch($this->fetch_mode());
				
				is_false($query->closeCursor(), true, "Fail to close the connection");
				
				$query = null;
				
				return $x;
			}
			
			/**
			 *
			 * Check if the driver is not the current
			 *
			 * @method not
			 *
			 * @param  string  $driver
			 *
			 * @return bool
			 *
			 */
			public function not( string $driver ) : bool
			{
				return $this->driver() !== $driver;
			}
			
			/**
			 *
			 * Execute a request and return result in an array
			 *
			 * @method request
			 *
			 * @param  string    $sql
			 * @param  string[]  $vars
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function request( string $sql, string ...$vars ) : array
			{
				
				$query = $this->pdo()->prepare($sql);
				
				$query->execute($vars);
				
				is_true(is_bool($query), true, $sql);
				
				$x = $query->fetchAll($this->fetch_mode());
				
				is_false($query->closeCursor(), true, "Fail to close the connection");
				
				$query = null;
				
				$sql = null;
				
				return $x;
			}
			
			/**
			 *
			 * Execute a query and return true on success or false on failure
			 *
			 * @method execute
			 *
			 * @param  string    $sql
			 * @param  string[]  $vars
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function execute( string $sql, string ...$vars ) : bool
			{
				$query = $this->pdo()->prepare($sql);
				
				is_true(is_bool($query), true, $sql);
				
				$x = $query->execute($vars);
				
				is_false($query->closeCursor(), true, "Fail to close the connection");
				
				$query = null;
				
				return $x;
				
			}
			
			/**
			 *
			 * Start a transaction block
			 *
			 * @throws Exception
			 *
			 * @return Connect
			 *
			 */
			public function transaction() : Connect
			{
				is_false($this->pdo()->beginTransaction(), true, "Transaction start fail");
				
				return $this;
			}
			
			/**
			 *
			 * Commit the current transaction
			 *
			 * @throws Exception
			 *
			 * @return bool
			 *
			 */
			public function commit() : bool
			{
				return $this->pdo()->commit();
			}
			
			/**
			 *
			 * Abort the current transaction
			 *
			 * @throws Exception
			 *
			 * @return Connect
			 *
			 */
			public function rollback() : Connect
			{
				is_false($this->pdo()->rollBack(), true, "ROLLBACK as fail");
				
				return $this;
			}
			
			/**
			 *
			 * @return string|PDO
			 *
			 */
			private function getpdo()
			{
				$database = $this->database;
				$username = $this->username;
				$password = $this->password;
				$driver = $this->driver;
				$host = $this->host;
				
				if ( is_null($this->instance) )
				{
					if ( $this->sqlite() )
					{
						if ( def($database) )
						{
							try
							{
								$this->instance = new PDO("$driver:$database");
							}
							catch ( PDOException $e )
							{
								return $e->getMessage();
							}
						}
						else
						{
							try
							{
								$this->instance = new PDO("$driver::memory:");
							}
							catch ( PDOException $e )
							{
								return $e->getMessage();
							}
						}
						
					}
					else
					{
						if ( def($database) )
						{
							try
							{
								$this->instance = new PDO("$driver:host=$host;dbname=$database", $username, $password);
							}
							catch ( PDOException $e )
							{
								return $e->getMessage();
							}
						}
						else
						{
							try
							{
								$this->instance = new PDO("$driver:host=$host;dbname=$database", $username, $password);
							}
							catch ( PDOException $e )
							{
								return $e->getMessage();
							}
						}
					}
					
				}
				else
				{
					return $this->instance;
				}
				
				$this->instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
				
				$this->instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				return $this->instance;
				
			}
			
		}
	}
