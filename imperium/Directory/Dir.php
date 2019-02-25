<?php

namespace Imperium\Directory {

    use Exception;
    use Imperium\File\File;

   /**
    *
    * Directory management
    *
    * @author Willy Micieli <micieli@laposte.net>
    *
    * @package imperium
    *
    * @version 4
    *
    * @license https://git.fumseck.eu/cgit/imperium/tree/LICENSE
    *
    **/
    class Dir
    {

        const IGNORE = array(
            '.gitignore',
            '.hgignore',
        );

        /**
         *
         * Remove all files in the directory expect ignore files
         *
         * @method clear
         *
         * @param string $directory The directory path to clear
         *
         * @return bool
         *
         * @throws Exception
         */
        public static function clear(string $directory): bool
        {
            self::create($directory);

            $files = array_diff(scandir($directory), array('.','..'));

            foreach ($files as $file)
            {
                if(not_in(self::IGNORE,$file))
                {
                    (self::is("$directory/$file")) ? self::clear("$directory/$file") : File::remove("$directory/$file");
                }
            }
            return true;
        }

        /**
         *
         * Create a new directory if not exist
         *
         * @method create
         *
         * @param  string $directory The name of directory
         *
         * @return bool
         *
         * @throws Exception
         * 
         */
        public static function create(string $directory): bool
        {
            return is_false(self::is($directory)) ? mkdir($directory):  false;
        }

        /**
         *
         * Remove a directory
         *
         * @method remove
         *
         * @param  string $directory  The directory to remove
         *
         * @return bool
         *
         */
        public static function remove(string $directory): bool
        {
            return self::is($directory) ? rmdir($directory) : false;
        }

        /**
         *
         * Check if is a directory
         *
         * @method is
         *
         * @param  string $directory The directory to check
         *
         * @return bool
         *
         */
        public static function is(string $directory): bool
        {
            return is_dir($directory);
        }
    }
}
