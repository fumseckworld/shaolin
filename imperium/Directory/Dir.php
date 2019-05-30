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
         * @param string $directory
         * @return bool
         * @throws Exception
         */
        public static function remove(string $directory): bool
        {

            if (self::is($directory))
            {
                $dir = opendir($directory);

                while(false !== ( $file = readdir($dir)) )
                {
                    if (( $file != '.' ) && ( $file != '..' ))
                    {
                        $full = $directory . '/' . $file;
                        if ( is_dir($full) )
                        {
                            self::remove($full);
                        }
                        else {
                            unlink($full);
                        }
                    }
                }
                closedir($dir);
                return rmdir($directory);
            }
            return false;
        }


        /**
         *
         * Create a structure
         *
         * @param string $source
         * @param string ...$dirs
         *
         * @throws Exception
         *
         */
        public static function structure(string $source,string ...$dirs): void
        {
            if (self::is($source))
                self::remove($source);

            self::create($source);
            foreach ($dirs as $dir)
                self::create("$source/$dir");
        }
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
         * 
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
         * Copy a dir
         *
         * @param string $source Source path
         * @param string $dest Destination path
         * @param int $permissions New folder creation permissions
         *
         * @return bool
         *
         * @throws Exception
         */
        public static function copy(string $source,string $dest,int $permissions = 0755): bool
        {
            
            if (is_link($source))
            {
                return symlink(readlink($source), $dest);
            }
            
            // Simple copy for a file
            if (is_file($source))
            {
                return copy($source, $dest);
            }
        
            // Make destination directory
            if (!self::is($dest))
            {
                self::create($dest,$permissions);
            }
        
            $dir = dir($source);

            while (false !== $entry = $dir->read()) 
            {
                // Skip pointers
                if ($entry == '.' || $entry == '..') 
                {
                    continue;
                }
        
                // Deep copy directories
                self::copy("$source/$entry", "$dest/$entry");
            }

            $dir->close();
            return true;
        
        }

        /**
         * @param string $dir
         * @param int $sorting_order
         * @return array
         */
        public static function scan(string $dir,$sorting_order = SCANDIR_SORT_ASCENDING ): array
        {
            return collection(scandir($dir,$sorting_order))->remove_value('.')->remove_value('..')->collection();
        }

        /**²
         *
         * Create a new directory if not exist
         *
         * @method create
         *
         * @param string $directory The name of directory
         *
         * @param int $permissions
         * @return bool
         *
         * @throws Exception
         */
        public static function create(string $directory,int $permissions = 0755): bool
        {
            return is_false(self::is($directory)) ? mkdir($directory,$permissions):  false;
        }

        /**
         *
         * Checkout on a new directory
         *
         * @param string $directory
         *
         * @return bool
         *
         */
        public static function checkout(string $directory): bool
        {
            return self::is($directory) ? chdir($directory) : false;
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

        /**
         *
         * Check if a dir has a subdirectory
         *
         * @param string $path
         * @param string[] $dirs
         *
         * @return bool
         *
         */
        public static function contains(string $path,string ...$dirs)
        {
            $result = collection();

            foreach ($dirs as $dir)
                $result->add(is_dir($path .DIRECTORY_SEPARATOR . $dir));

            return $result->not_exist(false);
        }
    }
}
