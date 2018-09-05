<?php
/**
 * fumseck added UserManagement.php to imperium
 * The 11/09/17 at 08:57
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

namespace Imperium\Databases\Core {


    use Imperium\Connexion\Connect;
    use Imperium\Databases\Eloquent\Users\Users;


    interface UserManagement
    {
        /**
         * delete an user
         *
         * @param string $user
         *
         * @return bool
         */
        public function drop(string $user): bool;

        /**
         * User constructor.
         *
         * @param Connect $connect
         */
        public function __construct(Connect $connect);

        /**
         * return all users
         *
         * @return array
         */
        public function show(): array;

        /**
         * define username
         *
         * @param string $name
         *
         * @return Users
         */
        public function set_name(string $name): Users;

        /**
         * define user password
         *
         * @param string $password
         *
         * @return Users
         */
        public function set_password(string $password): Users;

        /**
         * create a new user
         *
         * @return bool
         */
        public function create(): bool;

        /**
         * set user rights
         *
         * @param string $rights
         *
         * @return Users
         */
        public function set_rights(string $rights): Users;

        /**
         * check if a user exist
         *
         * @param string $user
         * @return bool
         */
        public function exist(string  $user = ''): bool;

        /**
         * update user password
         *
         * @param string $user
         * @param string $password
         *
         * @return bool
         */
        public function update_password(string $user, string $password): bool;

        /**
         * define hidden users
         *
         * @param array $users
         *
         * @return Users
         */
        public function hidden(array $users): Users;

    }
}