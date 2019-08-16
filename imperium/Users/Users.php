<?php
	
	namespace Imperium\Users
	{
		
		use Imperium\Connexion\Connect;
		use Imperium\Exception\Kedavra;
		
		/**
		 * Class Users
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Users
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Users
		{
			
			/**
			 *
			 * The user password
			 *
			 * @var string
			 *
			 */
			private $password;
			
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
			 * The connexion
			 *
			 * @var Connect
			 *
			 */
			private $connexion;
			
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
			 * Remove an user
			 *
			 * @param  string[]  $users
			 *
			 * @throws Kedavra
			 * @return bool
			 *
			 */
			public function drop(string ...$users) : bool
			{
				
				$driver = $this->driver;
				$this->check($driver);
				$data = collect();
				foreach($users as $user)
					$this->connexion->mysql() ? $data->set($this->connexion->execute("DROP USER '$user'@'localhost'")) : $data->set($this->connexion->execute("DROP ROLE $user"));
				
				return $data->ok();
			}
			
			/**
			 *
			 * Check if a server has users
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function has() : bool
			{
				
				return def($this->show());
			}
			
			/**
			 * show user
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function show() : array
			{
				
				$driver = $this->driver;
				$this->check($driver);
				$users = collect();
				$hidden = collect($this->hidden_users());
				$request = '';
				$this->connexion->mysql() ? assign(true, $request, "SELECT user from mysql.user") : assign(true, $request, "SELECT rolname FROM pg_roles");
				foreach($this->connexion->request($request) as $user)
				{
					$x = current($user);
					if($hidden->empty())
					{
						$users->push($x);
					}
					else
					{
						if($hidden->not_exist($x))
							$users->push($x);
					}
				}
				
				return $users->all();
			}
			
			/**
			 *
			 * Set the username
			 *
			 * @param  string  $name  The name
			 *
			 * @return Users
			 *
			 */
			public function set_name(string $name) : Users
			{
				
				$this->username = $name;
				
				return $this;
			}
			
			/**
			 *
			 * Set the user password
			 *
			 * @param  string  $password
			 *
			 * @return Users
			 *
			 */
			public function set_password(string $password) : Users
			{
				
				$this->password = $password;
				
				return $this;
			}
			
			/**
			 *
			 * Create the user
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function create() : bool
			{
				
				is_true(not_def($this->username, $this->password), true, "Missing values");
				$driver = $this->driver;
				$this->check($driver);
				
				return $this->connexion->mysql() ? $this->connexion->execute("CREATE USER '{$this->username}'@'localhost' IDENTIFIED BY '$this->password'") : $this->connexion->execute("CREATE ROLE $this->username PASSWORD '$this->password'");
			}
			
			/**
			 *
			 * Check if the user exist
			 *
			 * @param  string  $user
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function exist(string $user) : bool
			{
				
				return collect($this->show())->exist($user);
			}
			
			/**
			 *
			 * Check if the user exist
			 *
			 * @param  string  $user
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public function not_exist(string $user) : bool
			{
				
				return collect($this->show())->not_exist($user);
			}
			
			/**
			 * update user password
			 *
			 * @param  string  $user
			 * @param  string  $password
			 *
			 * @throws Kedavra
			 * @return bool
			 *
			 */
			public function update_password(string $user, string $password) : bool
			{
				
				$driver = $this->driver;
				$this->check($driver);
				
				return equal($driver, MYSQL) ? $this->connexion->execute("SET PASSWORD FOR '$user'@'localhost' = PASSWORD('$password');FLUSH PRIVILEGES;") : $this->connexion->execute("ALTER ROLE $user WITH PASSWORD '$password'");
			}
			
			/**
			 *
			 * User constructor.
			 *
			 * @param  Connect  $connect
			 *
			 */
			public function __construct(Connect $connect)
			{
				
				$this->connexion = $connect;
				$this->driver = $connect->driver();
			}
			
			/**
			 *
			 * Check if the driver can be have users
			 *
			 * @param  string  $driver
			 *
			 * @throws Kedavra
			 * @return Users
			 *
			 */
			public function check(string $driver) : Users
			{
				
				not_in([ MYSQL, POSTGRESQL ], $driver, true, "The $driver driver has not users");
				
				return $this;
			}
			
			/**
			 *
			 * Get all hidden user
			 *
			 * @throws Kedavra
			 *
			 * @return array
			 *
			 */
			public function hidden_users() : array
			{
				
				return db('hidden_users');
			}
			
		}
	}
