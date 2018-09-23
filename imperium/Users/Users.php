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
         * @param string $user
         *
         * @return bool
         *
         * @throws Exception
         */
        public function drop(string $user): bool
        {

            switch ($this->connexion->get_driver())
            {
                case Connect::MYSQL:
                    return $this->connexion->execute("DROP USER '$user'@'localhost'");
                break;

                case Connect::POSTGRESQL:
                    return $this->connexion->execute("DROP ROLE $user");
                break;
                default:
                    return false;
                break;
            }

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
            $users = collection();
            if (def($this->hidden))
                $hidden = collection($this->hidden);
            else
                $hidden = collection();
            switch ($this->connexion->get_driver())
            {
                case Connect::MYSQL:
                    foreach ($this->connexion->request("SELECT user from mysql.user") as $user)
                    {
                        $x = current($user);
                        if ($hidden->empty())
                        {

                            $users->push($x);
                        } else {
                            if ($hidden->not_exist($x))
                                $users->push($x);
                        }
                    }
                break;
                case Connect::POSTGRESQL:
                    foreach ($this->connexion->request( "SELECT rolname FROM pg_roles") as $user)
                    {
                        $x = current($user);
                        if ($hidden->empty())
                        {
                            $users->push($x);
                        } else {
                            if ($hidden->not_exist($x))
                                $users->push($x);
                        }
                    }
                break;
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
            switch ($this->connexion->get_driver())
            {
                case Connect::MYSQL:
                    return $this->connexion->execute("CREATE USER '$this->username'@'localhost' IDENTIFIED BY '$this->password' $this->rights");
                break;
                case Connect::POSTGRESQL:
                    return $this->connexion->execute("CREATE ROLE $this->username PASSWORD '$this->password' $this->rights");
                break;
                default:
                    return false;
                break;
            }
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
            switch ($this->connexion->get_driver())
            {
                case Connect::MYSQL:
                    return $this->connexion->execute("SET PASSWORD FOR '$user'@'localhost' = PASSWORD('$password');FLUSH PRIVILEGES");
                break;
                case Connect::POSTGRESQL:
                    return $this->connexion->execute("ALTER ROLE $user WITH PASSWORD '$password'");
                break;
                default:
                    return false;
                break;
            }
        }




        /**
         * User constructor.
         *
         * @param Connect $connect
         */
        public function __construct(Connect $connect)
        {
            $this->connexion = $connect;
        }
    }
}