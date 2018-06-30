<?php
/**
 * fumseck added OauthExceptions.php to imperium
 * The 09/09/17 at 17:05
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
 */

namespace Imperium\Auth\Exceptions {

    use Exception;

    class OauthExceptions extends Exception
    {
        /**
         * throw an exception if code digits length is not equal to 6
         *
         * @return static
         */
        public static function codeLengthIncorrect()
        {
            return new static('the length of the code must be 6 digits');
        }

        public static function invalidSecretCode()
        {
            return new static('Invalid secret code');
        }
    }
}
