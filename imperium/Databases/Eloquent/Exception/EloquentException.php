<?php
/**
 * fumseck added EloquentException.php to imperium
 * The 09/09/17 at 20:58
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
 *
 * @package : imperium
 * @author  : fumseck
 */

namespace Imperium\Databases\Eloquent\Exception {

    use Exception;

    class EloquentException extends Exception
    {
        /**
         * throw a exception if not found primary key
         *
         * @param string $table
         *
         * @return static
         */
        public static function notFoundPrimaryKey(string $table)
        {
            return new static("We have not found primary key inside table $table");
        }
    }
}