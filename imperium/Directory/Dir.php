<?php

	namespace Imperium\Directory
	{

		use Imperium\Exception\Kedavra;

		/**
		 *
		 * Class Dir
		 *
		 * @package Imperium\Directory
		 *
		 * @author Willy Micieli
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 */
		class Dir
		{

			const IGNORE = ['.gitignore', '.hgignore',];


			/**
			 *
			 *
			 * @param string $directory
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public static function remove(string $directory): bool
			{

				if (self::is($directory))
				{
					$dir = opendir($directory);

					while (false !== ($file = readdir($dir)))
					{
						if (($file != '.') && ($file != '..'))
						{
							$full = $directory . '/' . $file;
							if (is_dir($full))
							{
								self::remove($full);
							} else
							{
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
			 * @param string[] ...$dirs
			 *
			 * @throws Kedavra
			 * 
			 * @return bool
			 * 
			 */
			public static function structure(string $source, string ...$dirs): bool
			{
				$data = collect();
				self::is($source) ?  self::remove($source) && self::create($source) :  	self::create($source);

				foreach ($dirs as $dir)
					$data->push(self::create("$source/$dir"));
				
				return $data->ok();
			}

			/**
			 *
			 * Remove all files in the directory expect ignore files
			 *
			 * @method clear
			 *
			 * @param string $directory The directory path to clear
			 *
			 * @throws Kedavra
			 *
			 * @return bool
			 *
			 */
			public static function clear(string $directory): bool
			{
				self::create($directory);

				$files = array_diff(scandir($directory), ['.', '..']);

				foreach ($files as $file)
				{
					if (not_in(self::IGNORE, $file))
					{
						(self::is("$directory/$file")) ? self::clear("$directory/$file") : unlink("$directory/$file");
					}
				}
				return true;
			}

			/**
			 *
			 * Check if a directory exist
			 *
			 * @param string $directory
			 *
			 * @return bool
			 *
			 */
			public static function exist(string $directory): bool
			{
				return file_exists($directory);
			}

			/**
			 *
			 * Copy a dir
			 *
			 * @param string $source      Source path
			 * @param string $dest        Destination path
			 * @param int    $permissions New folder creation permissions
			 *
			 * @throws Kedavra
			 * @return bool
			 *
			 */
			public static function copy(string $source, string $dest, int $permissions = 0755): bool
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
					self::create($dest, $permissions);
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
			 * @param int    $sorting_order
			 *
			 * @return array
			 */
			public static function scan(string $dir, $sorting_order = SCANDIR_SORT_ASCENDING): array
			{
				return collect(scandir($dir, $sorting_order))->del('.','..','.gitignore')->all();
			}

			/**²
			 *
			 * Create a new directory if not exist
			 *
			 * @method create
			 *
			 * @param string $directory The name of directory
			 *
			 * @param int    $permissions
			 *
			 * @throws Kedavra
			 * @return bool
			 *
			 */
			public static function create(string $directory, int $permissions = 0755): bool
			{
				return is_false(self::is($directory)) ? mkdir($directory, $permissions) : self::remove($directory) && mkdir($directory,$permissions);
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
			 * @param string $directory The directory to check
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
			 * @param string   $path
			 * @param string[] $dirs
			 *
			 * @return bool
			 *
			 */
			public static function contains(string $path, string ...$dirs)
			{
				$result = collect();

				foreach ($dirs as $dir)
					$result->push(is_dir($path . DIRECTORY_SEPARATOR . $dir));

				return $result->ok();
			}
		}
	}
