<?php
/**
 * fumseck added Users.php to imperium
 * The 11/09/17 at 08:56
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package : imperium
 * @author  : fumseck
 **/


namespace Imperium\Databases\Eloquent\Users {

    use Imperium\Databases\Core\UserManagement;
    use Imperium\Databases\Eloquent\Connexion\Connexion;
    use Imperium\Databases\Eloquent\Share;
    use PDO;
    use PDOException;

    class Users implements UserManagement
    {
        use Share;

        /**
         * delete an user
         *
         * @param string $user
         *
         * @return bool
         */
        public function drop(string $user): bool
        {

            switch ($this->driver)
            {
                case Connexion::MYSQL:
                    return execute($this->getInstance(),"DROP USER '$user'@'localhost'");
                break;

                case Connexion::POSTGRESQL:
                    return execute($this->getInstance(),"DROP ROLE $user;");
                break;
                case Connexion::ORACLE:
                    return execute($this->getInstance(),"DROP USER $user");
                break;

                default:
                    return false;
                break;
            }

        }

        /**
         * return all users
         *
         * @return array
         */
        public function show(): array
        {
            $users = array();

            switch ($this->driver)
            {
                case Connexion::MYSQL:
                    try{
                        $query = $this->getInstance()->prepare('SELECT user from mysql.user');
                        $query->execute();
                    }catch (PDOException $e)
                    {
                        return $users;
                    }
                break;
                case Connexion::POSTGRESQL:
                    try{
                        $query = $this->getInstance()->prepare('SELECT rolname FROM pg_roles;');
                        $query->execute();
                    }catch (PDOException $e)
                    {
                        return $users;
                    }

                break;
                case Connexion::ORACLE:
                    try{
                        $query = $this->getInstance()->prepare('select username from dba_users;');
                        $query->execute();
                    }catch (PDOException $e)
                    {
                        return $users;
                    }

                break;
                default:
                    return $users;
                break;
            }

            foreach ($query->fetchAll() as $user)
            {
                if (!empty($this->hidden))
                {
                    if (!has(current($user), $this->hidden))
                    {
                        push($users, current($user));
                    }
                } else {
                    push($users, current($user));
                }

            }
            return $users;
        }

        /**
         * define username
         *
         * @param string $name
         *
         * @return Users
         */
        public function setName(string $name): Users
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
        public function setPassword(string $password): Users
        {
            $this->password = $password;

            return $this;
        }

        /**
         * create a new user
         *
         * @return bool
         */
        public function create(): bool
        {
            return userAdd($this->driver,$this->username,$this->password,$this->rights,$this->getInstance());

        }

        /**
         * set user rights
         *
         * @param string $rights
         *
         * @return Users
         */
        public function setRights(string $rights): Users
        {
            $this->rights = $rights;
            return $this;
        }

        /**
         * check if a user exist
         *
         * @param string $user
         * @return bool
         */
        public function exist(string  $user = ''): bool
        {
            if (!empty($user))
                return has($user,$this->show());
            else
                return has($this->username,$this->show());
        }

        /**
         * update user password
         *
         * @param string $user
         * @param string $password
         *
         * @return bool
         */
        public function updatePassword(string $user, string $password): bool
        {
            switch ($this->driver)
            {
                case Connexion::MYSQL:
                    return execute($this->getInstance(),"SET PASSWORD FOR '$user'@'localhost' = PASSWORD('$password');FLUSH PRIVILEGES");
                break;
                case Connexion::POSTGRESQL:
                    return execute($this->getInstance(),"ALTER ROLE $user WITH PASSWORD '$password'");
                break;
                case Connexion::ORACLE:
                    return execute($this->getInstance(),"ALTER USER $user IDENTIFIED BY $password;");
                break;
                default:
                    return false;
                break;
            }
        }

        /**
         * define hidden users
         *
         * @param array $users
         *
         * @return Users
         */
        public function setHidden(array $users): Users
        {
            $this->hidden = $users;

            return $this;
        }

        /**
         * define user type
         *
         * @param string $driver
         *
         * @return Users
         */
        public function setDriver(string $driver): Users
        {
            $this->driver = $driver;

            return $this;
        }

        /**
         * start query builder
         *
         * @return Users
         */
        public static function manage(): Users
        {
            return new static();
        }

        /**
         * Get a pdo instance
         *
         * @return null|PDO
         */
        public function getInstance()
        {
            switch ($this->driver)
            {
                case Connexion::SQLITE:
                    if (empty($this->database))
                        return connect($this->driver);
                    else
                        return connect($this->driver,$this->database);
                break;

                default:
                    if (empty($this->database))
                        return connect($this->driver,'',$this->username,$this->password);
                    return connect($this->driver,$this->database,$this->username,$this->password);

                break;
            }
        }
    }
}