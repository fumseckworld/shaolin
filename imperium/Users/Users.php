<?php
/**
 * fumseck added Users.php to imperium
 * The 11/09/17 at 08:56
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General public License for more details.
 *
 * You should have received a copy of the GNU General public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package : imperium
 * @author  : fumseck
 **/


namespace Imperium\Users {


    use Exception;
    use Imperium\Connexion\Connect;

    /**
     * Management of users
     *
     * @package Imperium\Users
     *
     */
    class Users 
    { 

        /**
         * @var string
         */
        private $rights;

        /**
         * user password
         *
         * @var string
         */
        private $password;

        /**
         * username
         *
         * @var string
         */
        private $username;

        /**
         * @var Connect 
         */
        private $connexion;
        
        /**
         * @var array
         */
        private $hidden;

        /**
         * @var string
         */
        private $driver;

        /**
         * @param string $user
         *
         * @return bool
         *
         * @throws Exception
         */
        public function drop(string $user): bool
        {
            $driver = $this->driver;

            $this->check($driver);

            return equal($driver,Connect::MYSQL) ?  $this->connexion->execute("DROP USER '$user'@'localhost'") :  $this->connexion->execute("DROP ROLE $user");
        }


        /**
         *
         * Check if a server has users
         *
         * @return bool
         *
         * @throws Exception
         *
         */
        public function has(): bool
        {
            return def($this->show());
        }

        /**
         * Set hidden users
         *
         * @param array $hidden
         *
         * @return Users
         */
        public function hidden(array $hidden): Users
        {
            $this->hidden = $hidden;

            return $this;
        }

        /**
         * show user
         *
         * @return array
         *
         * @throws Exception
         */
        public function show(): array
        {
            $driver = $this->driver;

            $this->check($driver);

            $users = collection();

            $hidden = def($this->hidden) ? collection($this->hidden) : collection();

            $request = '';

            equal($driver,Connect::MYSQL) ?  assign(true,$request,"SELECT user from mysql.user") :   assign(true,$request,"SELECT rolname FROM pg_roles");

            foreach ($this->connexion->request($request) as $user)
            {
                $x = current($user);
                if ($hidden->empty())
                {
                    $users->push($x);
                } else
                {
                    if ($hidden->not_exist($x))
                        $users->push($x);
                }
            }

            return $users->collection();
        }

        /**
         * define username
         *
         * @param string $name
         *
         * @return Users
         */
        public function set_name(string $name): Users
        {
            $this->username = $name;

            return $this;
        }

        /**
         * define user password
         *
         * @param string $password
         *
         * @return Users
         */
        public function set_password(string $password): Users
        {
            $this->password = $password;

            return $this;
        }

        /**
         * @return bool
         *
         * @throws Exception
         */
        public function create(): bool
        {
            $driver = $this->driver;
            $this->check($driver);

            return equal($driver,Connect::MYSQL) ? $this->connexion->execute("CREATE USER '$this->username'@'localhost' IDENTIFIED BY '$this->password' $this->rights") : $this->connexion->execute("CREATE ROLE $this->username PASSWORD '$this->password' $this->rights");
        }

        /**
         * set user rights
         *
         * @param string $rights
         *
         * @return Users
         */
        public function set_rights(string $rights): Users
        {
            $this->rights = $rights;
            
            return $this;
        }

        /**
         * @param string $user
         *
         * @return bool
         *
         * @throws Exception
         */
        public function exist(string  $user): bool
        {
            return collection($this->show())->exist($user);
        }

        /**
         * update user password
         *
         * @param string $user
         * @param string $password
         *
         * @return bool
         *
         * @throws Exception
         */
        public function update_password(string $user, string $password): bool
        {
            $driver = $this->driver;
            $this->check($driver);

            return equal($driver,Connect::MYSQL) ? $this->connexion->execute("SET PASSWORD FOR '$user'@'localhost' = PASSWORD('$password');FLUSH PRIVILEGES") : $this->connexion->execute( "ALTER ROLE $user WITH PASSWORD '$password'");
        }


        /**
         * User constructor.
         *
         * @param Connect $connect
         */
        public function __construct(Connect $connect)
        {
            $this->connexion = $connect;
            $this->driver = $connect->get_driver();
        }

        /**
         * @param $driver
         * @throws Exception
         */
        public function check($driver)
        {
            not_in([Connect::MYSQL, Connect::POSTGRESQL], $driver, true, "The current driver is not supported");
        }
    }
}