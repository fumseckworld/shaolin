<?php
	
	namespace Imperium\Import
	{
		
		use Exception;
		use Imperium\Connexion\Connect;
		use Imperium\Exception\Kedavra;
		
		/**
		 * Class Import
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Import
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Import
		{
			
			/**
			 *
			 * the connexion
			 *
			 * @var Connect
			 *
			 */
			private $connexion;
			
			/**
			 *
			 * the sql file
			 *
			 * @var string
			 */
			private $sql_file;
			
			/**
			 *
			 * The current driver
			 *
			 * @var string
			 *
			 */
			private $driver;
			
			/**
			 *
			 * The base name
			 *
			 * @var string
			 *
			 */
			private $base;
			
			/**
			 * Import constructor.
			 *
			 * @throws Exception
			 */
			public function __construct()
			{
				
				$this->connexion = app()->connect();
				$this->driver = $this->connexion->driver();
				$this->base = $this->connexion->base();
				$this->sql_file = sql_file();
			}
			
			/**
			 *
			 * Import the data
			 *
			 * @method import
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function import() : bool
			{
				
				$password = $this->connexion->password();
				$username = $this->connexion->user();
				$host = $this->connexion->host();
				$base = $this->base;
				$sql = $this->sql_file;
				switch($this->driver)
				{
					case MYSQL:
						return is_not_false(system("mysql -u $username -h $host -p$password $base < $sql"));
					break;
					case POSTGRESQL:
						return is_not_false(system(" psql  -h $host  -U $username $base < $sql"));
					break;
					case SQLITE:
						return is_not_false(system("sqlite3 $base < $sql"));
					break;
					default:
						return false;
					break;
				}
			}
			
		}
	}
