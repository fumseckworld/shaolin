<?php

namespace Imperium\Directory {

    use Imperium\File\File;

    class Dir
    {

        const IGNORE = array(
            '.gitignore',
            '.hgignore',
        );

        /**
         *
         * Remove the directory  files
         *
         * @param $directory
         *
         * @return bool
         *
         * @throws \Exception
         *
         */
        public static function clear(string $directory): bool
        {
           if (!self::create($directory))
           {
               $files = array_diff(scandir($directory), array('.','..'));
               foreach ($files as $file)
               {
                   if(not_in(self::IGNORE,$file))
                   {
                       (self::is("$directory/$file")) ? self::clear("$directory/$file") : File::remove("$directory/$file");
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