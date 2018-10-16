<?php
/**
 * fumseck added Dir.php to imperium
 * The 09/09/17 at 13:16
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

namespace Imperium\Directory {

    use Imperium\File\File;

    class Dir
    {

        const IGNORE = array(
            '.gitignore',
            '.hgignore',
        );

        /**
         * delete a folder with files
         *
         * @param $directory
         *
         * @return bool
         *
         * @throws \Exception
         */
        public static function clear(string $directory)
        {
           if (!self::create($directory))
           {
               $files = array_diff(scandir($directory), array('.','..'));
               foreach ($files as $file)
               {
                   if(not_in(self::IGNORE,$file))
                   {
                       (self::is("$directory/$file")) ? self::clear("$directory/$file") : File::delete("$directory/$file");
                   }

               }
           }
            return true;
        }

        /**
         * create a new directory
         *
         * @param string $directory
         *
         * @return bool
         */
        public static function create(string $directory): bool
        {
            return !self::is($directory) ? mkdir($directory):  false;
        }

        /**
         * remove  a  directory
         *
         * @param string $directory
         *
         * @return bool
         */
        public static function remove(string $directory): bool
        {
            return self::is($directory) ? rmdir($directory) : false;
        }

        /**
         * check if param is a directory
         *
         * @param string $directory
         *
         * @return bool
         */
        public static function is(string $directory): bool
        {
             return is_dir($directory);
        }
    }
}