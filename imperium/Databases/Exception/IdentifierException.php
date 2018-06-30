<?php
/**
 * fumseck added HelpersExecption.php to imperium
 * The 11/09/17 at 15:50
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


namespace Imperium\Databases\Exception {

    use Exception;

    class IdentifierException extends Exception
    {
        public static function incorrectIdentifiers()
        {
            return new static('Incorrect identifiers');
        }
    }
}