<?php
/**
 * fumseck added CannotSetParameter.php to imperium
 * The 09/09/17 at 13:19
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

namespace  Imperium\Databases\Dumper\Exceptions {

    use Exception;

    class CannotSetParameter extends Exception
    {
        /**
         * @param string $name
         * @param string $conflictName
         * @return CannotSetParameter
         */
        public static function conflictingParameters($name, $conflictName)
        {
            return new static("Cannot set `{$name}` because it conflicts with parameter `{$conflictName}`.");
        }
    }
}